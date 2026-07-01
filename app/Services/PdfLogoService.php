<?php

namespace App\Services;

/**
 * Produces a base64 data: URI for a company logo, resized to a small
 * PDF-friendly thumbnail via GD. Avoids OOM from embedding multi-MB images.
 */
class PdfLogoService
{
    /**
     * Build a base64-encoded PNG data URI from a logo filename.
     * Returns null when the logo is missing, unreadable, or too large.
     */
    public function buildDataUri(string $logoName, int $maxW = 200, int $maxH = 80): ?string
    {
        if ($logoName === '') {
            return null;
        }

        $path = FCPATH . 'uploads/logos/' . $logoName;

        if (!is_file($path)) {
            return null;
        }

        // Hard caps — refuse absurdly large files
        if (filesize($path) > 8_000_000) {
            return null;
        }

        $info = @getimagesize($path);
        if (!$info) {
            return null;
        }

        [$w, $h, $type] = $info;
        if ($w <= 0 || $h <= 0) {
            return null;
        }
        if ($w * $h > 25_000_000) {
            return null;
        }

        // Small enough? Embed as-is.
        if ($w <= $maxW && $h <= $maxH && filesize($path) < 60_000) {
            $mime = image_type_to_mime_type($type);
            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
        }

        // No GD? Fall back to raw embedding.
        if (!function_exists('imagecreatetruecolor')) {
            $mime = image_type_to_mime_type($type);
            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
        }

        $src = null;
        switch ($type) {
            case IMAGETYPE_PNG:
                $src = @imagecreatefrompng($path);
                break;
            case IMAGETYPE_JPEG:
                $src = @imagecreatefromjpeg($path);
                break;
            case IMAGETYPE_WEBP:
                $src = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : null;
                break;
        }

        if (!$src) {
            return null;
        }

        $ratio = min($maxW / $w, $maxH / $h, 1.0);
        $newW  = max(1, (int) round($w * $ratio));
        $newH  = max(1, (int) round($h * $ratio));

        $dst = imagecreatetruecolor($newW, $newH);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefill($dst, 0, 0, $transparent);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);

        ob_start();
        imagepng($dst, null, 6);
        $bin = ob_get_clean();
        imagedestroy($src);
        imagedestroy($dst);

        if ($bin === false) {
            return null;
        }

        return 'data:image/png;base64,' . base64_encode($bin);
    }
}
