<?php

namespace App\Services;

/**
 * Interface for processors
 */
interface ProcessorInterface
{
    /**
     * Process data based on the process type
     *
     * @param int $processType
     * @param mixed $data
     * @return mixed
     */
    public function process(int $processType, mixed $data): mixed;
}
