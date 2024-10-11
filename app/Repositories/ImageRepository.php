<?php

namespace App\Repositories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Collection;

/**
 * ImageRepository class
 */
class ImageRepository
{
    /**
     * Sync images for a product
     *
     * This method synchronizes the images for a product by creating new images
     * that are not already present in the database and deleting images that are
     * no longer present in the provided image links.
     *
     * @param int $productId
     * @param Collection $existingImages
     * @param array $imageNames
     *
     * @return void
     */
    public function syncImages(
        int $productId,
        Collection $existingImages,
        array $imageNames
    ): void {
        $existingImageNames = $existingImages
            ->pluck('name')
            ->toArray();

        foreach ($imageNames as $index => $imageName) {
            if (!in_array($imageName, $existingImageNames)) {
                Image::create(
                    [
                        'product_id' => $productId,
                        'name' => $imageName,
                        'index' => $index,
                    ]
                );
            }
        }

        Image::where('product_id', $productId)
            ->whereNotIn('name', $imageNames)
            ->delete();
    }
}
