<?php

namespace Theme\TLCommerce\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
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
                'max:1048576',
            ],
            'mobile_frames' => [
                'nullable',
                'file',
                'mimes:zip',
                'max:1048576',
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

            $json = json_encode(
                $manifest,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
            );

            if (!Storage::disk('public')->put($this->manifestPath(), $json)) {
                throw new \RuntimeException(
                    'Failed to write scroll hero manifest: ' . $this->manifestPath()
                );
            }

            cache()->forget(
                tenantCacheKey('scroll-hero-manifest')
            );

            toastNotification(
                'success',
                'Scroll hero updated successfully'
            );

            return redirect()->back();
        } catch (\Throwable $e) {
            $errorId = (string) Str::uuid();

            Log::error('Scroll hero upload failed', [
                'error_id' => $errorId,
                'exception_class' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'host' => $request->getHost(),
                'php_version' => PHP_VERSION,
                'zip_extension_loaded' => extension_loaded('zip'),
                'zip_archive_available' => class_exists(ZipArchive::class),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time'),
                'desktop_zip_bytes' => $request->file('desktop_frames')?->getSize(),
                'mobile_zip_bytes' => $request->file('mobile_frames')?->getSize(),
                'exception' => $e,
            ]);

            toastNotification(
                'error',
                'Scroll hero upload failed. Error ID: ' . $errorId
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

            if (!Storage::disk('public')->put($storagePath, $contents)) {
                throw new \RuntimeException(
                    'Failed to write scroll hero frame: ' . $storagePath
                );
            }

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
