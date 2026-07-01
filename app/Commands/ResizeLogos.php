<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ResizeLogos extends BaseCommand
{
    protected $group       = 'app';
    protected $name        = 'logos:resize';
    protected $description = 'Downscale oversized logos in uploads/logos to a max 1000px edge. Fixes existing large logos so they can be embedded in PDFs without OOM.';
    protected $usage       = 'logos:resize [max_dim]';
    protected $arguments   = [
        'max_dim' => 'Maximum edge length in pixels. Default: 1000',
    ];

    public function run(array $params)
    {
        @ini_set('memory_limit', '1024M');

        $dir    = FCPATH . 'uploads/logos';
        $maxDim = (int) ($params[0] ?? 1000);
        if ($maxDim < 100) $maxDim = 1000;

        if (!is_dir($dir)) {
            CLI::error('Directory not found: ' . $dir);
            return;
        }

        $files = glob($dir . '/*.{png,jpg,jpeg,webp}', GLOB_BRACE);
        if (!$files) {
            CLI::write('No logo files found.', 'yellow');
            return;
        }

        CLI::write('Scanning ' . count($files) . ' file(s) for resize...', 'light_cyan');

        $resized = 0;
        $skipped = 0;
        $failed  = 0;

        foreach ($files as $path) {
            $name = basename($path);
            $info = @getimagesize($path);
            if (!$info) {
                CLI::write('  SKIP ' . $name . ' (unreadable)', 'yellow');
                $failed++;
                continue;
            }
            [$w, $h, $type] = $info;
            if ($w <= $maxDim && $h <= $maxDim) {
                $skipped++;
                continue;
            }
            // Safety: refuse to decode absurdly large rasters
            if ($w * $h > 80_000_000) {
                CLI::write('  SKIP ' . $name . " ({$w}x{$h} too large, would OOM)", 'red');
                $failed++;
                continue;
            }

            CLI::write("  Resizing {$name} ({$w}x{$h}) → max {$maxDim}px...", 'light_cyan');

            $src = null;
            switch ($type) {
                case IMAGETYPE_PNG:  $src = @imagecreatefrompng($path);  break;
                case IMAGETYPE_JPEG: $src = @imagecreatefromjpeg($path); break;
                case IMAGETYPE_WEBP:
                    $src = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : null;
                    break;
                default:
                    CLI::write('    SKIP unsupported type', 'yellow');
                    $failed++;
                    continue 2;
            }
            if (!$src) {
                CLI::write('    FAILED to decode', 'red');
                $failed++;
                continue;
            }

            $ratio = min($maxDim / $w, $maxDim / $h, 1.0);
            $newW  = max(1, (int) round($w * $ratio));
            $newH  = max(1, (int) round($h * $ratio));

            $dst = imagecreatetruecolor($newW, $newH);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
            imagefill($dst, 0, 0, $transparent);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);
            imagedestroy($src);

            $before = filesize($path);

            switch ($type) {
                case IMAGETYPE_PNG:  imagepng($dst, $path, 6);  break;
                case IMAGETYPE_JPEG: imagejpeg($dst, $path, 85); break;
                case IMAGETYPE_WEBP:
                    if (function_exists('imagewebp')) imagewebp($dst, $path, 85);
                    break;
            }
            imagedestroy($dst);

            clearstatcache(true, $path);
            $after = filesize($path);
            CLI::write("    OK → {$newW}x{$newH}, " . $this->fmtSize($before) . ' → ' . $this->fmtSize($after), 'green');
            $resized++;
        }

        CLI::newLine();
        CLI::write('Done.', 'light_cyan');
        CLI::write("  Resized: {$resized}", 'green');
        CLI::write("  Already small: {$skipped}");
        if ($failed > 0) CLI::write("  Skipped/failed: {$failed}", 'yellow');
    }

    private function fmtSize(int $bytes): string {
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 2) . ' MB';
    }
}
