<?php

namespace App\ValueObject;

/**
 * Class for representing the result of a spreads request to OpenAiService.
 *
 * This class provides a convenient way to access the data returned by OpenAiService.
 *
 * @package App\Services
 */
class ImageDescriptionResult
{
    /**
     * @var array
     */
    private array $data;

    /**
     * Constructor.
     *
     * @param array $data The data returned by OpenAiService.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Gets the image description returned by OpenAiService.
     *
     * @return string The image description returned by OpenAiService.
     */
    public function getImageDescription(): string
    {
        return $this->data['content'][0]['text'] ?? '';
    }

    /**
     * Gets the error message returned by OpenAiService.
     *
     * @return string|null The error message returned by OpenAiService.
     */
    public function getErrorMessage(): ?string
    {
        return $this->data['error'] ?? null;
    }

    /**
     * Gets the error code returned by OpenAiService.
     *
     * @return int|null The error code returned by OpenAiService.
     */
    public function getErrorCode(): ?int
    {
        return $this->data['code'] ?? null;
    }
}
