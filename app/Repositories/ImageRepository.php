<?php

namespace App\Repositories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

/**
 * ImageRepository class
 */
class ImageRepository
{
    /**
     * Constant for no description text
     */
    private const NO_DESCRIPTION_TEXT = '';

    /**
     * Find an image by a given attribute
     *
     * @param  string  $attribute  The attribute to search by
     * @param  mixed  $value  The value of the attribute to search for
     * @return Image|null The found image or null if not found
     */
    public function findByAttribute(string $attribute, mixed $value): ?Image
    {
        if (! in_array($attribute, (new Image())->getFillable())) {
            throw new InvalidArgumentException("Invalid attribute '$attribute'");
        }

        return Image::where($attribute, $value)->first();
    }

    /**
     * Sync images for a product
     *
     * This method synchronizes the images for a product by creating new images
     * that are not already present in the database and deleting images that are
     * no longer present in the provided image links.
     *
     * @param  int  $productId  The ID of the product
     * @param  Collection  $existingImages  The existing images for the product
     * @param  array  $imageNames  The names of the images to sync
     */
    public function syncImages(
        int $productId,
        Collection $existingImages,
        array $imageNames
    ): void {
        $existingImageNames = $existingImages->pluck('name')->toArray();

        foreach ($imageNames as $index => $imageName) {
            if (! in_array($imageName, $existingImageNames)) {
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

    /**
     * Set the description of an image to "No Description".
     *
     * @param  Image  $image  The image to update
     * @param  string  $description  The description to set (defaults to "No Description")
     */
    public function setDescription(
        Image $image,
        string $description = self::NO_DESCRIPTION_TEXT
    ): void {
        $image->setAttribute('description', $description);
        $image->save();
    }

    /**
     * Check if the image needs a description.
     *
     * @param  Image  $image  The image object
     * @return bool True if the image needs a description, false otherwise
     */
    public function imageNeedDescription(Image $image): bool
    {
        return
            !$image->getAttribute('description')
            || $image->getAttribute('description') === self::NO_DESCRIPTION_TEXT;
    }
}
