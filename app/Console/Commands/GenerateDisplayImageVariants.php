<?php

namespace App\Console\Commands;

use Core\Models\UploadedFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class GenerateDisplayImageVariants extends Command
{
    /**
     * @var string
     */
    protected $signature = 'media:generate-display-variants
                            {--tenant= : Tenant id (UUID) to initialize before running}
                            {--limit=0 : Max files to process (0 = all)}
                            {--dry-run : List candidates without writing files}';

    /**
     * @var string
     */
    protected $description = 'Backfill aspect-preserving w1600/w800 display variants for uploaded images (falls back to originals if missing)';

    /**
     * @return int
     */
    public function handle()
    {
        $tenantId = $this->option('tenant');
        if (!empty($tenantId)) {
            $tenant = \App\Models\Tenant::find($tenantId);
            if (!$tenant) {
                $this->error("Tenant not found: {$tenantId}");
                return 1;
            }
            tenancy()->initialize($tenant);
            $this->info("Initialized tenant: {$tenantId}");
        }

        $limit = (int) $this->option('limit');
        $dryRun = (bool) $this->option('dry-run');

        $query = UploadedFile::query()
            ->where(function ($q) {
                $q->whereIn('file_type', ['image', 'jpg', 'jpeg', 'png', 'gif', 'bmp'])
                    ->orWhereIn('extension', ['jpg', 'jpeg', 'png', 'gif', 'bmp']);
            })
            ->orderBy('id');

        if ($limit > 0) {
            $query->limit($limit);
        }

        $files = $query->get();
        $this->info('Candidates: ' . $files->count());

        $created = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($files as $file) {
            $path = $file->path;
            // Central uploads may be stored as storage/...
            $relative = preg_replace('#^storage/#', '', $path);
            $disk = $file->disk ?: 'public';
            $destination = $file->folder_name;

            if (empty($destination)) {
                $destination = trim(dirname($relative), '/.\\');
            }

            if ($dryRun) {
                $this->line("[dry-run] id={$file->id} path={$path}");
                $skipped++;
                continue;
            }

            try {
                if ($disk !== 'amazons3') {
                    $localSource = 'public/' . $relative;
                    if (!File::exists($localSource)) {
                        // Try path as stored
                        $alt = 'public/' . ltrim($path, '/');
                        if (File::exists($alt)) {
                            $localSource = $alt;
                            $relative = ltrim($path, '/');
                        } else {
                            $this->warn("Missing file id={$file->id} {$localSource}");
                            $failed++;
                            continue;
                        }
                    }
                } else {
                    $localSource = null;
                }

                $tokens = generateDisplayImageVariants(
                    $relative,
                    $destination,
                    $disk === 'amazons3' ? 'amazons3' : 'public',
                    null,
                    $disk === 'amazons3' ? null : $localSource
                );

                if (is_array($tokens) && count($tokens) > 0) {
                    $created++;
                    $this->line("OK id={$file->id} " . implode(',', $tokens));
                } else {
                    $skipped++;
                }

                // Bust forever cache for this media id so getDisplayImagePath can resolve new files
                Cache::forget('file-path' . $file->id);
                Cache::forget('home-page-desktop-slider-display-v1-' . $file->id);
                Cache::forget('home-page-mobile-slider-display-v1-' . $file->id);
            } catch (\Exception $e) {
                $failed++;
                $this->warn("Fail id={$file->id}: " . $e->getMessage());
            }
        }

        $this->info("Done. created/updated={$created} skipped={$skipped} failed={$failed}");
        $this->comment('Note: page-builder section cache TTL is 1h; wait or clear tenant cache if banners still show old URLs.');

        return 0;
    }
}
