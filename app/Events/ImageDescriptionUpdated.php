<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Event triggered when an image description is updated.
 */
class ImageDescriptionUpdated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var int The product ID
     */
    public int $productId;

    /**
     * @var int The percentage of processed images
     */
    public int $percentage;

    /**
     * Create a new event instance.
     *
     * @param int $productId The product ID
     * @param int $percentage The percentage of processed images
     */
    public function __construct(int $productId, int $percentage)
    {
        $this->productId = $productId;
        $this->percentage = $percentage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('image-description-updated'),
        ];
    }

    /**
     * Log the event.
     */
    public function log(): void
    {
        Log::info("Image description updated for product ID: {$this->productId} - {$this->percentage}%");
    }
}