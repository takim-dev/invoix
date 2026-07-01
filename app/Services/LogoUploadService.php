<?php

namespace App\Services;

use CodeIgniter\HTTP\UploadedFile;

/**
 * Handles logo upload validation, storage, and resizing.
 * Used by both admin app-logo upload and company logo upload.
 */
class LogoUploadService
{
    private const ALLOWED_MIMES = ['image/png', 'image/jpeg', 'image/webp'];
    private const ALLOWED_EXT   = ['png', 'jpg', 'jpeg', 'webp'];
    private const MAX_SIZE      = 2 * 1024 * 1024;  // 2 MB

    private string $uploadDir;

    public function __construct()
    {
        $this->uploadDir = FCPATH . 'uploads/logos';
    }

    /**
     * Validate and store an uploaded logo file.
     * Returns the new filename on success, null if no file was provided.
     * Throws \RuntimeException on validation failure.
     */
    public function handle(?UploadedFile $file): ?string
    {
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return null;
        }

        if (!in_array($file->getMimeType(), self::ALLOWED_MIMES, true)) {
            throw new \RuntimeException('Invalid file type. Allowed: PNG, JPG, WEBP.');
        }

        if (!in_array(strtolower($file->getExtension()), self::ALLOWED_EXT, true)) {
            throw new \RuntimeException('Invalid file extension. Allowed: PNG, JPG, WEBP.');
        }

        if ($file->getSize() > self::MAX_SIZE) {
            throw new \RuntimeException('File too large. Maximum size is 2 MB.');
        }

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        $newName = $file->getRandomName();
        $file->move($this->uploadDir, $newName);

        $this->downscale($this->uploadDir . '/' . $newName);

        return $newName;
    }

    /**
     * Delete a previously uploaded logo file.
     */
    public function delete(?string $filename): void
    {
        if (empty($filename)) {
            return;
        }

        $path = $this->uploadDir . '/' . basename($filename);

        if (is_file($path)) {
            @unlink($path);
        }
    }

    /**
     * Build the public URL path for a logo filename.
     */
    public function publicPath(string $filename): string
    {
        return '/uploads/logos/' . $filename;
    }

    /**
     * Downscale logo to max 1000px longest edge to prevent OOM in PDF generation.
     */
    private function downscale(string $path, int $maxDim = 1000): void
    {
        if (!function_exists('imagecreatetruecolor')) {
            return;
        }

        $info = @getimagesize($path);
        if (!$info) {
            return;
        }

        [$w, $h, $type] = $info;
        if ($w <= 0 || $h <= 0 || max($w, $h) <= $maxDim) {
            return;
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
            return;
        }

        $ratio  = min($maxDim / $w, $maxDim / $h, 1.0);
        $newW   = max(1, (int) round($w * $ratio));
        $newH   = max(1, (int) round($h * $ratio));
        $dst    = imagecreatetruecolor($newW, $newH);

        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefill($dst, 0, 0, $transparent);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);

        switch ($type) {
            case IMAGETYPE_PNG:
                imagepng($dst, $path, 6);
                break;
            case IMAGETYPE_JPEG:
                imagejpeg($dst, $path, 85);
                break;
            case IMAGETYPE_WEBP:
                function_exists('imagewebp') && imagewebp($dst, $path, 85);
                break;
        }

        imagedestroy($src);
        imagedestroy($dst);
    }
}
