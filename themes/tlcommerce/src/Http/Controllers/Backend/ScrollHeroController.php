<?php

namespace Theme\TLCommerce\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class ScrollHeroController extends Controller
{
    public function index()
    {
        $manifest = $this->getManifest();

        return view(
            'theme/tlcommerce::backend.scroll-hero.index',
            compact('manifest')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'desktop_frames' => [
                'required_without:mobile_frames',
                'file',
                'mimes:zip',
                'max:102400',
            ],
            'mobile_frames' => [
                'nullable',
                'file',
                'mimes:zip',
                'max:102400',
            ],
            'scroll_height' => [
                'nullable',
                'integer',
                'min:150',
                'max:600',
            ],
        ]);

        try {
            $manifest = $this->getManifest();

            if ($request->hasFile('desktop_frames')) {
                $manifest['desktop'] = $this->extractFrames(
                    $request->file('desktop_frames'),
                    'desktop'
                );
            }

            if ($request->hasFile('mobile_frames')) {
                $manifest['mobile'] = $this->extractFrames(
                    $request->file('mobile_frames'),
                    'mobile'
                );
            }

            $manifest['enabled'] = true;
            $manifest['scroll_height'] = (int) $request->input(
                'scroll_height',
                300
            );

            Storage::disk('public')->put(
                $this->manifestPath(),
                json_encode(
                    $manifest,
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );

            cache()->forget(
                tenantCacheKey('scroll-hero-manifest')
            );

            toastNotification(
                'success',
                'Scroll hero updated successfully'
            );

            return redirect()->back();
        } catch (\Throwable $e) {
            report($e);

            toastNotification(
                'error',
                'Scroll hero upload failed'
            );

            return redirect()->back();
        }
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'enabled' => ['required', 'boolean'],
        ]);

        try {
            $manifest = $this->getManifest();
            $manifest['enabled'] = $request->boolean('enabled');

            Storage::disk('public')->put(
                $this->manifestPath(),
                json_encode(
                    $manifest,
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );

            cache()->forget(
                tenantCacheKey('scroll-hero-manifest')
            );

            toastNotification(
                'success',
                'Scroll hero status updated'
            );
        } catch (\Throwable $e) {
            report($e);

            toastNotification(
                'error',
                'Status update failed'
            );
        }

        return redirect()->back();
    }

    public function destroy()
    {
        try {
            Storage::disk('public')->deleteDirectory(
                $this->basePath()
            );

            cache()->forget(
                tenantCacheKey('scroll-hero-manifest')
            );

            toastNotification(
                'success',
                'Scroll hero frames deleted'
            );
        } catch (\Throwable $e) {
            report($e);

            toastNotification(
                'error',
                'Delete failed'
            );
        }

        return redirect()->back();
    }

    private function extractFrames($zipFile, string $device): array
    {
        $zip = new ZipArchive();

        if ($zip->open($zipFile->getRealPath()) !== true) {
            throw new \RuntimeException('Unable to open ZIP file.');
        }

        $devicePath = $this->basePath() . '/' . $device;

        Storage::disk('public')->deleteDirectory($devicePath);
        Storage::disk('public')->makeDirectory($devicePath);

        $frames = [];

        for ($index = 0; $index < $zip->numFiles; $index++) {
            $originalName = $zip->getNameIndex($index);

            if (!$originalName || str_ends_with($originalName, '/')) {
                continue;
            }

            $extension = strtolower(
                pathinfo($originalName, PATHINFO_EXTENSION)
            );

            if (!in_array($extension, ['webp', 'avif', 'jpg', 'jpeg', 'png'])) {
                continue;
            }

            $contents = $zip->getFromIndex($index);

            if ($contents === false) {
                continue;
            }

            $filename = sprintf(
                'frame-%05d.%s',
                count($frames) + 1,
                $extension
            );

            $storagePath = $devicePath . '/' . $filename;

            Storage::disk('public')->put(
                $storagePath,
                $contents
            );

            $frames[] = Storage::disk('public')->url(
                $storagePath
            );
        }

        $zip->close();

        if (count($frames) < 2) {
            throw new \RuntimeException(
                'The ZIP must contain at least two valid image frames.'
            );
        }

        return $frames;
    }

    private function getManifest(): array
    {
        return cache()->remember(
            tenantCacheKey('scroll-hero-manifest'),
            3600,
            function () {
                if (!Storage::disk('public')->exists(
                    $this->manifestPath()
                )) {
                    return [
                        'enabled' => false,
                        'desktop' => [],
                        'mobile' => [],
                        'scroll_height' => 300,
                    ];
                }

                $manifest = json_decode(
                    Storage::disk('public')->get(
                        $this->manifestPath()
                    ),
                    true
                );

                return is_array($manifest)
                    ? $manifest
                    : [
                        'enabled' => false,
                        'desktop' => [],
                        'mobile' => [],
                        'scroll_height' => 300,
                    ];
            }
        );
    }

    private function basePath(): string
    {
        /*
         * tenantCacheKey keeps each tenant's animation separate.
         * Str::slug converts it into a safe folder name.
         */
        $tenantFolder = Str::slug(
            tenantCacheKey('tlcommerce-scroll-hero')
        );

        return 'tlcommerce/scroll-hero/' . $tenantFolder;
    }

    private function manifestPath(): string
    {
        return $this->basePath() . '/manifest.json';
    }

    /**
     * Return Scroll Hero configuration for the Vue frontend.
     */
    public function manifest()
    {
        try {
            $manifest = $this->getManifest();

            if (
                empty($manifest['enabled']) ||
                empty($manifest['desktop'])
            ) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'enabled' => false,
                        'desktop' => [],
                        'mobile' => [],
                        'scroll_height' => 300,
                    ],
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'enabled' => true,

                    'desktop' => array_map(
                        fn($frame) => url($frame),
                        $manifest['desktop'] ?? []
                    ),

                    'mobile' => array_map(
                        fn($frame) => url($frame),
                        $manifest['mobile'] ?? []
                    ),

                    'scroll_height' => max(
                        150,
                        min(
                            600,
                            (int) ($manifest['scroll_height'] ?? 300)
                        )
                    ),
                ],
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'data' => [
                    'enabled' => false,
                    'desktop' => [],
                    'mobile' => [],
                    'scroll_height' => 300,
                ],
            ]);
        }
    }
}
