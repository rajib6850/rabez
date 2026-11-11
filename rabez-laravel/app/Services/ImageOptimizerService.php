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

