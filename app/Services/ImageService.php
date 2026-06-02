<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    public function uploadAndCompressImages($images, string $subFolder = 'products'): array
    {
        $imagePaths = [];
        if (!is_array($images)) {
            $images = [$images];
        }
        $targetDir = storage_path("app/public/{$subFolder}");
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        foreach ($images as $imageFile) {
            if ($imageFile && $imageFile->isValid()) {
                $filename = Str::random(20) . '_' . uniqid() . '.webp';
                $fullPath = $targetDir . '/' . $filename;
                try {
                    Image::read($imageFile)
                        ->scaleDown(width: 1200)
                        ->toWebp(80)
                        ->save($fullPath);
                    $imagePaths[] = "/storage/{$subFolder}/" . $filename;
                } catch (\Exception $e) {
                    logger("Failed to process image in {$subFolder}: " . $e->getMessage());
                }
            }
        }
        return $imagePaths;
    }


    public function uploadAndCompressSingleImage($file, string $folder = 'banners', int $width = 1200, int $height = 450): string
    {
        if (!$file || !$file->isValid()) {
            throw new \Exception("Invalid image file asset uploaded.");
        }

        $fileName = $folder . '/' . uniqid() . '.webp';

        $manager = new ImageManager(new Driver());
        $image = $manager->read($file)
            ->cover($width, $height)
            ->toWebp(75); // جودة 75% حاسمة لـ LCP خفيف وسريع جداً

        Storage::disk('public')->put($fileName, (string) $image);

        return '/storage/' . $fileName;
    }


    public function deletePhysicalImages(array $pathsToStorage): void
    {
        foreach ($pathsToStorage as $path) {
            if (empty($path)) continue;

            // تحويل الرابط العام مثل /storage/products/x.webp إلى المسار الحقيقي app/public/products/x.webp
            $relativePath = str_replace('/storage/', 'public/', $path);

            if (Storage::exists($relativePath)) {
                Storage::delete($relativePath);
            }
        }
    }
}
