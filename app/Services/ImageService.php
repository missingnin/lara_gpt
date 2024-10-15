<?php

namespace App\Services;

use App\Events\ImageDescriptionUpdated;
use App\Models\Product;
use App\Repositories\ImageRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * Image service class
 *
 * This class provides a way to interact with images and synchronize them with products.
 *
 * @package App\Services
 */
class ImageService implements ImageServiceInterface
{
    /**
     * @var ImageRepository Image repository instance
     */
    private ImageRepository $imageRepository;

    /**
     * @var Client GuzzleHttp\Client instance
     */
    private Client $httpClient;

    /**
     * Constructor
     *
     * Initializes the image repository and HTTP client instances
     *
     * @param ImageRepository $imageRepository
     */
    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
        $this->httpClient = new Client();
    }

    /**
     * Synchronizes images with a product
     *
     * Synchronizes the images with the product and returns the updated image collection
     *
     * @param string $imagesPrompt An images Prompt Text
     * @param array $images        An array of images to synchronize
     * @param Product $product     The product to synchronize images with
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
     * Check if an image is accessible by its URL.
     *
     * Sends a HEAD request to the image URL and checks if the response status code is 200
     *
     * @param string $imageUrl The image URL to check
     * @return bool True if the image is accessible, false otherwise
     */
    public function isImageUrlAccessible(string $imageUrl): bool
    {
        try {
            $response = $this->httpClient->head($imageUrl);

            return $response->getStatusCode() === 200;
        } catch (GuzzleException) {
            return false;
        }
    }

    /**
     * Handles image description and dispatches an event with the percentage of processed images.
     *
     * @param string $imageDescription
     * @param string $imageUrl
     * @return void
     */
    public function handleImageDescription(string $imageDescription, string $imageUrl): void
    {
        $image = $this->imageRepository->findByAttribute('name', $imageUrl);

        if (!$image) {
            throw new ModelNotFoundException();
        }

        $product = $image->product()->first();

        if (!$product) {
            throw new ModelNotFoundException();
        }

        $image->setAttribute('description', $imageDescription);
        $image->save();

        $percentage = $this
            ->imageRepository
            ->getImagesWithDescriptionPercentage(
                $product->getAttribute('id')
            );

        event(new ImageDescriptionUpdated($product->id, $percentage));
    }
}
