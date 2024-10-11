<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ImageRepository;
use Illuminate\Support\Collection;

/**
 * Image service class
 */
class ImageService implements ImageServiceInterface
{
    private ImageRepository $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    /**
     * Synchronizes images with a product
     *
     * @param array $images An array of images to synchronize
     * @param Product $product
     * @return Collection
     */
    public function syncImages(array $images, Product $product): Collection
    {
        $this->imageRepository->syncImages(
            $product->getAttribute('id'),
            $product->images()->get(),
            $images,
        );

        return $product->images()->get();
    }
}
