<?php

namespace App\ValueObject;

/**
 * Abstract class for representing the result of a request to OpenAiService.
 */
abstract class OpenAiResult
{
    /**
     * @var array
     */
    protected array $data;

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
     * Gets the content returned by OpenAiService.
     *
     * @return ?string The image description returned by OpenAiService.
     */
    public function getContent(): ?string
    {
        return $this->data['content'] ?? null;
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
