<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Gd\Encoders\JpegEncoder;
use Intervention\Image\ImageManager;

class ImageService extends Controller
{
    public static function processAndSaveImage($image, $path, $quality = 75)
    {
        $uniqueFileName = uniqid().'.jpg';
        $imagePath = $path.'/'.$uniqueFileName;

        $manager = new ImageManager(new Driver);
        $image = $manager->read($image->getPathname());

        // Jen komprese do JPG s požadovanou kvalitou
        $encoded = $image->encode(new JpegEncoder($quality));

        Storage::disk('public')->put($imagePath, (string) $encoded); // zde jse musel přetypovat

        return $imagePath;
    }

    public static function deleteImage($imagePath)
    {
        if (!$imagePath) {
            return; // Pokud není cesta, není co mazat
        }

        $fullPath = public_path('storage/'.$imagePath);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}

