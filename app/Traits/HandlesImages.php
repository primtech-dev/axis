<?php

namespace App\Traits;

trait HandlesImages
{
    /**
     * @throws \Exception
     */
    public function saveImageAsWebp($image, $directory = 'uploads'): string
    {
        $fileName = time() . '-' . uniqid() . '.webp';
        $path = storage_path("app/public/{$directory}/{$fileName}");

        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $mime = $image->getMimeType();
        $img = null;

        switch ($mime) {
            case 'image/jpeg':
                $img = imagecreatefromjpeg($image->getPathname());
                break;
            case 'image/png':
                $img = imagecreatefrompng($image->getPathname());
                imagepalettetotruecolor($img);
                break;
            case 'image/webp':
                $image->storeAs("public/{$directory}", $fileName);
                return "{$directory}/{$fileName}";
            default:
                throw new \Exception("Format image tidak didukung: $mime");
        }

        imagewebp($img, $path, 90);
        imagedestroy($img);

        return "{$directory}/{$fileName}";
    }

    public function deleteImage($relativePath): void
    {
        if (!$relativePath) {
            return;
        }

        $fullPath = storage_path('app/public/' . $relativePath);

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}
