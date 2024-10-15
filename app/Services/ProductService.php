<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ImageRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class for product services.
 */
class ProductService implements ProductServiceInterface
{
    /**
     * The image repository instance.
     *
     * @var ImageRepository
     */
    private ImageRepository $imageRepository;

    /**
     * Creates a new instance of the product service.
     *
     * @param ImageRepository $imageRepository The image repository instance.
     */
    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    /**
     * Synchronizes images with a product.
     *
     * Synchronizes the images with the product and returns the updated image collection.
     *
     * @param string $imagesPrompt An images Prompt Text
     * @param array $images        An array of images to synchronize
     * @param Product $product     The product to synchronize images with
     *
     * @return Collection The updated image collection
     */
    public function syncImages(string $imagesPrompt, array $images, Product $product): Collection
    {
        $this->imageRepository->syncImages(
            $product->getAttribute('id'),
            $product->images()->get(),
            $images,
            $imagesPrompt
        );

        return $product->images()->get();
    }

    /**
     * Get a collection of images of the product that need a description update.
     *
     * @param string $imagesPrompt
     * @param Collection $images
     * @return Collection A collection of images that need a description update
     */
    public function imagesForUpdatingDescription(string $imagesPrompt, Collection $images): Collection
    {
        return $images->filter(function ($image) use ($imagesPrompt) {
            return
                empty($image->getAttribute('description'))
                || $image->getAttribute('description') === $this->imageRepository::NO_DESCRIPTION_TEXT
                || $image->getAttribute('prompt') !== $imagesPrompt;
        });
    }
}
