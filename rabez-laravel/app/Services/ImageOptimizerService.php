<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageOptimizerService
{
    /**
     * Process, auto-orient (iPhone EXIF), downscale, convert to WebP, and save to database.
     *
     * @param UploadedFile|string $file
     * @param string $folder
     * @param int $maxWidth
     * @param int $quality
     * @return Media
     */
    public function optimizeAndStore($file, string $folder = 'uploads/gallery', int $maxWidth = 1400, int $quality = 82): Media
    {
        $destDir = public_path($folder);
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        $isUploaded = $file instanceof UploadedFile;
        $originalName = $isUploaded ? $file->getClientOriginalName() : basename($file);
        $tempPath = $isUploaded ? $file->getRealPath() : $file;

        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        $slug = Str::slug($baseName);
        if (empty($slug)) {
            $slug = 'jersey-photo';
        }
        $filename = $slug . '-' . time() . '-' . Str::random(5) . '.webp';
        $destPath = $destDir . DIRECTORY_SEPARATOR . $filename;
        $relativeUrl = '/' . trim(str_replace('\\', '/', $folder), '/') . '/' . $filename;
        $relativePath = trim(str_replace('\\', '/', $folder), '/') . '/' . $filename;

        $width = 0;
        $height = 0;
        $mime = 'image/webp';
        $processed = false;

        // Try GD processing
        try {
            $imageResource = $this->loadImage($tempPath);
            if ($imageResource) {
                // 1. Auto-orient iPhone photos using EXIF
                $imageResource = $this->autoOrientExif($imageResource, $tempPath);

                $origW = imagesx($imageResource);
                $origH = imagesy($imageResource);

                // 2. Downscale if larger than maxWidth
                if ($origW > $maxWidth || $origH > $maxWidth) {
                    if ($origW >= $origH) {
                        $newW = $maxWidth;
                        $newH = (int) round(($origH / $origW) * $maxWidth);
                    } else {
                        $newH = $maxWidth;
                        $newW = (int) round(($origW / $origH) * $maxWidth);
                    }
                } else {
                    $newW = $origW;
                    $newH = $origH;
                }

                $canvas = imagecreatetruecolor($newW, $newH);
                // Handle alpha transparency
                imagealphablending($canvas, false);
                imagesavealpha($canvas, true);
                $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
                imagefilledrectangle($canvas, 0, 0, $newW, $newH, $transparent);

                imagecopyresampled($canvas, $imageResource, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

                // 3. Save as WebP
                if (function_exists('imagewebp')) {
                    imagewebp($canvas, $destPath, $quality);
                    $processed = true;
                    $width = $newW;
                    $height = $newH;
                }

                imagedestroy($canvas);
                imagedestroy($imageResource);
            }
        } catch (\Throwable $e) {
            // GD failed or file type not natively decodeable
            $processed = false;
        }

        // Fallback: If GD couldn't decode, copy as original
        if (!$processed || !file_exists($destPath)) {
            $ext = $isUploaded ? $file->getClientOriginalExtension() : pathinfo($file, PATHINFO_EXTENSION);
            $filename = $slug . '-' . time() . '-' . Str::random(5) . '.' . ($ext ?: 'jpg');
            $destPath = $destDir . DIRECTORY_SEPARATOR . $filename;
            $relativeUrl = '/' . trim(str_replace('\\', '/', $folder), '/') . '/' . $filename;
            $relativePath = trim(str_replace('\\', '/', $folder), '/') . '/' . $filename;

            if ($isUploaded) {
                $file->move($destDir, $filename);
            } else {
                copy($tempPath, $destPath);
            }

            $size = @getimagesize($destPath);
            if ($size) {
                $width = $size[0];
                $height = $size[1];
                $mime = $size['mime'] ?? 'image/jpeg';
            }
        }

        $fileSize = file_exists($destPath) ? filesize($destPath) : 0;

        return Media::create([
            'filename'      => $filename,
            'original_name' => $originalName,
            'path'          => $relativePath,
            'url'           => $relativeUrl,
            'mime_type'     => $mime,
            'file_size'     => $fileSize,
            'width'         => $width,
            'height'        => $height,
            'folder'        => $folder,
            'alt_text'      => str_replace(['-', '_'], ' ', $baseName),
        ]);
    }

    /**
     * Store an optimized square-bounded user avatar (max 400px, WebP when GD supports it)
     * and remove any previous local avatar file belonging to the user.
     */
    public function storeOptimizedAvatar(UploadedFile $file, int $userId, ?string $oldAvatarUrl = null): string
    {
        $publicDir = public_path('uploads/avatars');
        if (!is_dir($publicDir)) {
            @mkdir($publicDir, 0755, true);
        }

        $baseName = 'avatar-' . $userId . '-' . time() . '-' . bin2hex(random_bytes(4));
        $fileName = null;
        $destPath = null;

        $realPath = $file->getRealPath();
        $source = $realPath ? $this->loadImage($realPath) : null;

        if ($source) {
            $source = $this->autoOrientExif($source, $realPath);
            $origW = imagesx($source);
            $origH = imagesy($source);

            $maxDim = 400;
            $scale = min(1.0, $maxDim / max(1, $origW), $maxDim / max(1, $origH));
            $targetW = max(1, (int) round($origW * $scale));
            $targetH = max(1, (int) round($origH * $scale));

            $dest = imagecreatetruecolor($targetW, $targetH);
            imagealphablending($dest, false);
            imagesavealpha($dest, true);
            $transparent = imagecolorallocatealpha($dest, 0, 0, 0, 127);
            imagefilledrectangle($dest, 0, 0, $targetW, $targetH, $transparent);
            imagecopyresampled($dest, $source, 0, 0, 0, 0, $targetW, $targetH, $origW, $origH);

            if (function_exists('imagewebp')) {
                $candidateName = $baseName . '.webp';
                $candidatePath = $publicDir . DIRECTORY_SEPARATOR . $candidateName;
                if (@imagewebp($dest, $candidatePath, 86)) {
                    $fileName = $candidateName;
                    $destPath = $candidatePath;
                }
            }

            imagedestroy($dest);
            imagedestroy($source);
        }

        if (!$destPath || !is_file($destPath)) {
            $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                $ext = 'jpg';
            }
            $fileName = $baseName . '.' . $ext;
            $file->move($publicDir, $fileName);
            $destPath = $publicDir . DIRECTORY_SEPARATOR . $fileName;
        }

        $mirrorDir = $this->resolveMirrorAvatarDir();
        if ($mirrorDir && is_file($destPath)) {
            @copy($destPath, $mirrorDir . DIRECTORY_SEPARATOR . $fileName);
        }

        $this->deleteAvatarFile($oldAvatarUrl);

        return '/uploads/avatars/' . $fileName;
    }

    /**
     * Delete a previously uploaded local avatar file (including cPanel document-root mirror).
     * External OAuth avatar URLs are intentionally untouched.
     */
    public function deleteAvatarFile(?string $avatarUrl): void
    {
        if (empty($avatarUrl)) {
            return;
        }

        $path = parse_url(trim($avatarUrl), PHP_URL_PATH) ?? '';
        $normalized = '/' . ltrim(str_replace('\\', '/', $path), '/');
        if (!str_starts_with($normalized, '/uploads/avatars/')) {
            return;
        }

        $fileName = basename($normalized);
        if ($fileName === '' || $fileName === '.' || $fileName === '..') {
            return;
        }

        $publicFile = public_path('uploads/avatars/' . $fileName);
        if (is_file($publicFile)) {
            @unlink($publicFile);
        }

        $mirrorDir = $this->resolveMirrorAvatarDir(false);
        if ($mirrorDir) {
            $mirrorFile = $mirrorDir . DIRECTORY_SEPARATOR . $fileName;
            if (is_file($mirrorFile)) {
                @unlink($mirrorFile);
            }
        }
    }

    /**
     * Resolve cPanel document-root uploads/avatars path when distinct from Laravel public_path().
     */
    protected function resolveMirrorAvatarDir(bool $createIfMissing = true): ?string
    {
        $docRoot = !empty($_SERVER['DOCUMENT_ROOT']) ? rtrim((string) $_SERVER['DOCUMENT_ROOT'], '/\\') : null;
        if (!$docRoot || !is_dir($docRoot)) {
            return null;
        }

        $publicReal = realpath(public_path());
        $docReal = realpath($docRoot);
        if ($publicReal && $docReal && $publicReal === $docReal) {
            return null;
        }

        $mirrorDir = $docRoot . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'avatars';
        if (!is_dir($mirrorDir) && $createIfMissing) {
            @mkdir($mirrorDir, 0755, true);
        }

        return is_dir($mirrorDir) ? $mirrorDir : null;
    }

    /**
     * Load image resource from path using appropriate GD function
     */
    protected function loadImage(string $path)
    {
        $info = @getimagesize($path);
        if (!$info) {
            // Try string decode
            $data = @file_get_contents($path);
            return $data ? @imagecreatefromstring($data) : false;
        }

        switch ($info[2]) {
            case IMAGETYPE_JPEG:
                return @imagecreatefromjpeg($path);
            case IMAGETYPE_PNG:
                return @imagecreatefrompng($path);
            case IMAGETYPE_WEBP:
                return @imagecreatefromwebp($path);
            case IMAGETYPE_GIF:
                return @imagecreatefromgif($path);
            case IMAGETYPE_BMP:
                return @imagecreatefrombmp($path);
            default:
                $data = @file_get_contents($path);
                return $data ? @imagecreatefromstring($data) : false;
        }
    }

    /**
     * Fix orientation for iPhone photos using EXIF Orientation tag
     */
    protected function autoOrientExif($image, string $path)
    {
        if (!function_exists('exif_read_data')) {
            return $image;
        }

        $exif = @exif_read_data($path);
        if (!$exif || !isset($exif['Orientation'])) {
            return $image;
        }

        switch ((int) $exif['Orientation']) {
            case 3:
                $rotated = imagerotate($image, 180, 0);
                imagedestroy($image);
                return $rotated;
            case 6:
                // 90 deg CW rotation (iPhone portrait)
                $rotated = imagerotate($image, -90, 0);
                imagedestroy($image);
                return $rotated;
            case 8:
                // 90 deg CCW rotation
                $rotated = imagerotate($image, 90, 0);
                imagedestroy($image);
                return $rotated;
            default:
                return $image;
        }
    }

    /**
     * Scan existing product images in assets/images/products and index in Media
     */
    public function syncExistingProductImages(): int
    {
        $dir = public_path('assets/images/products');
        if (!is_dir($dir)) {
            return 0;
        }

        $files = scandir($dir);
        $count = 0;

        foreach ($files as $file) {
            if (in_array($file, ['.', '..'])) continue;
            $fullPath = $dir . DIRECTORY_SEPARATOR . $file;
            if (!is_file($fullPath)) continue;

            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) continue;

            $relativePath = 'assets/images/products/' . $file;
            $url = '/' . $relativePath;

            // Check if already in media table
            $exists = Media::where('path', $relativePath)->orWhere('url', $url)->exists();
            if ($exists) continue;

            $size = @getimagesize($fullPath);
            $fileSize = filesize($fullPath);

            Media::create([
                'filename'      => $file,
                'original_name' => $file,
                'path'          => $relativePath,
                'url'           => $url,
                'mime_type'     => $size ? $size['mime'] : 'image/jpeg',
                'file_size'     => $fileSize,
                'width'         => $size ? $size[0] : null,
                'height'        => $size ? $size[1] : null,
                'folder'        => 'products',
                'alt_text'      => ucwords(str_replace(['-', '_', '.' . $ext], ' ', $file)),
            ]);
            $count++;
        }

        return $count;
    }
}

