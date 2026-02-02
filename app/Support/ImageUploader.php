<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageUploader
{
    public static function uploadWebp(
        UploadedFile $file,
        string $directory,
        int $maxWidth = 1600,
        int $quality = 80
    ): string {
        $manager = new ImageManager(new Driver());

        $image = $manager->read($file->getPathname());

        // resize only if larger
        if ($image->width() > $maxWidth) {
            $image->scaleDown($maxWidth);
        }

        $filename = Str::uuid() . '.webp';
        $path = trim($directory, '/') . '/' . $filename;

        $webp = $image->toWebp($quality);

        Storage::disk('public')->put($path, (string) $webp);

        return $path;
    }
}
