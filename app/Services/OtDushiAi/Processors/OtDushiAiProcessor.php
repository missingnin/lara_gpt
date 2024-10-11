<?php

namespace App\Services\OtDushiAi\Processors;

use App\Constants\OtDushiAiProcessTypes;
use App\Exceptions\InvalidProcessTypeException;

/**
 * Class for OtDushi AI processors
 */
class OtDushiAiProcessor
{
    /**
     * Process data based on the process type
     *
     * @param mixed $data
     * @param string $processType
     *
     * @return mixed
     * @throws InvalidProcessTypeException
     */
    public function process($data, $processType): mixed
    {
        return match ($processType) {
            OtDushiAiProcessTypes::GET_AI_IMAGES_DESCRIPTION => $this->processImageDescription($data),
            OtDushiAiProcessTypes::GET_AI_SPREADS_GROUPS => $this->processSpreadsGroups($data),
            default => throw new InvalidProcessTypeException($processType),
        };
    }

    /**
     * Process image description
     *
     * @param mixed $data
     * @return mixed
     */
    protected function processImageDescription($data)
    {
        return;
    }

    /**
     * Process spreads groups
     *
     * @param mixed $data
     * @return mixed
     */
    protected function processSpreadsGroups($data)
    {
        return;
    }
}