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

    /**
     * FIX (root cause of out-of-order / repeated / missing frames):
     *
     * The previous version wrote frames as frame-00001, frame-00002, ...
     * in whatever order ZipArchive happened to iterate the archive's
     * internal file table (for ($index = 0; $index < $zip->numFiles; ...)).
     * That is NOT guaranteed to be numeric order — a zip built on macOS (via
     * Finder "Compress" or zip -r) commonly stores entries in an order
     * that sorts lexicographically (1, 10, 100, 11, 2, 20, ...) rather than
     * numerically, so the renamed sequence silently attached the WRONG
     * image content to each sequential frame number. No amount of sorting
     * on the frontend can fix that, because the frontend only ever sees
     * the already-mismatched final filenames.
     *
     * On top of that, a macOS-created zip typically also contains a
     * __MACOSX/ directory full of AppleDouble resource-fork files named
     * ._<originalname> with the SAME extension as the real image (e.g.
     * __MACOSX/._12.png). The old code had no filter for these, so they
     * were treated as real frames: each one consumed a slot in the
     * sequence (shifting every real frame's number after it) and, since
     * they are not valid image data, fail to decode in the browser --
     * which is exactly what showed up as "frames repeating" (the canvas
     * falls back to the nearest frame that did decode) and "frames
     * missing" (a real frame's image data effectively vanished behind a
     * ghost entry).
     *
     * The fix: (1) skip __MACOSX/ and ._-prefixed entries outright,
     * (2) extract the numeric sequence from each ORIGINAL filename and
     * sort on that before assigning new sequential names, so the frame
     * that was named 12.png in the zip is guaranteed to end up as the
     * 12th frame regardless of the zip's internal storage order, and
     * (3) validate that each entry is actually decodable image data
     * before writing it, skipping (and logging) anything that isn't.
     */
    private function extractFrames($zipFile, string $device): array
    {
        $zip = new ZipArchive();

        if ($zip->open($zipFile->getRealPath()) !== true) {
            throw new \RuntimeException('Unable to open ZIP file.');
        }

        $devicePath = $this->basePath() . '/' . $device;

        Storage::disk('public')->deleteDirectory($devicePath);
        Storage::disk('public')->makeDirectory($devicePath);

        // Pass 1: collect every valid image entry with its contents and a
        // sort key derived from the ORIGINAL filename, ignoring whatever
        // order the zip itself stores entries in.
        $entries = [];
        $skippedGhosts = 0;
        $skippedInvalid = 0;

        for ($index = 0; $index < $zip->numFiles; $index++) {
            $originalName = $zip->getNameIndex($index);

            if (!$originalName || str_ends_with($originalName, '/')) {
                continue;
            }

            $basename = basename($originalName);

            // Skip macOS AppleDouble/resource-fork junk and any
            // __MACOSX metadata folder — these are not real frames.
            if (
                str_contains($originalName, '__MACOSX/') ||
                str_starts_with($basename, '._') ||
                $basename === '.DS_Store' ||
                strcasecmp($basename, 'Thumbs.db') === 0
            ) {
                $skippedGhosts++;
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

            // Make sure this is genuinely decodable image data before we
            // ever write it out and count it as a frame.
            if (@getimagesizefromstring($contents) === false) {
                $skippedInvalid++;
                Log::warning('Scroll hero: skipped non-image zip entry', [
                    'device' => $device,
                    'entry' => $originalName,
                ]);
                continue;
            }

            $entries[] = [
                'sort_key' => $this->extractSequenceNumber($basename, count($entries)),
                'name' => $originalName,
                'contents' => $contents,
                'extension' => $extension,
            ];
        }

        $zip->close();

        if ($skippedGhosts > 0) {
            Log::info('Scroll hero: skipped non-frame zip entries', [
                'device' => $device,
                'skipped_ghost_entries' => $skippedGhosts,
                'skipped_invalid_images' => $skippedInvalid,
            ]);
        }

        // Sort by the number embedded in the ORIGINAL filename (e.g.
        // "12.png" -> 12), not by the order the zip stored entries in.
        // This is what actually fixes out-of-order playback.
        usort($entries, function ($a, $b) {
            if ($a['sort_key'] === $b['sort_key']) {
                // Tie-break deterministically (e.g. duplicate numbers)
                // instead of leaving equal-key entries in arbitrary order.
                return strnatcasecmp($a['name'], $b['name']);
            }

            return $a['sort_key'] <=> $b['sort_key'];
        });

        // Pass 2: write frames out in the now-correct order, drop exact
        // duplicate numbers (keep the first) so a re-used frame number in
        // the zip can't create a repeated/stuck-looking frame, and log
        // any gap left in the sequence so it's visible instead of silent.
        $frames = [];
        $seenKeys = [];
        $droppedDuplicates = 0;
        $previousKey = null;
        $gaps = [];

        foreach ($entries as $entry) {
            if (in_array($entry['sort_key'], $seenKeys, true)) {
                $droppedDuplicates++;
                continue;
            }

            $seenKeys[] = $entry['sort_key'];

            if ($previousKey !== null && $entry['sort_key'] - $previousKey > 1) {
                $gaps[] = "{$previousKey} -> {$entry['sort_key']}";
            }

            $previousKey = $entry['sort_key'];

            $filename = sprintf(
                'frame-%05d.%s',
                count($frames) + 1,
                $entry['extension']
            );

            $storagePath = $devicePath . '/' . $filename;

            if (!Storage::disk('public')->put($storagePath, $entry['contents'])) {
                throw new \RuntimeException(
                    'Failed to write scroll hero frame: ' . $storagePath
                );
            }

            $frames[] = Storage::disk('public')->url(
                $storagePath
            );
        }

        if ($droppedDuplicates > 0 || !empty($gaps)) {
            Log::warning('Scroll hero: irregular frame numbering in upload', [
                'device' => $device,
                'dropped_duplicate_numbers' => $droppedDuplicates,
                'gaps_in_sequence' => $gaps,
            ]);
        }

        if (count($frames) < 2) {
            throw new \RuntimeException(
                'The ZIP must contain at least two valid image frames.'
            );
        }

        return $frames;
    }

    /**
     * Extract the frame's intended position from its original filename
     * (e.g. "12.png" -> 12, "frame_007.webp" -> 7). Falls back to the
     * given default (its raw position in the zip) only when the filename
     * has no digits at all, so a fully non-numeric naming scheme doesn't
     * crash the sort — it just keeps whatever order it was found in.
     */
    private function extractSequenceNumber(string $basename, int $fallback): int
    {
        $nameWithoutExtension = pathinfo($basename, PATHINFO_FILENAME);

        if (preg_match('/(\d+)(?!.*\d)/', $nameWithoutExtension, $matches)) {
            return (int) $matches[1];
        }

        return $fallback;
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