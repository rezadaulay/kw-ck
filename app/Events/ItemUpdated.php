<?php

namespace App\Events;

use App\Item;
use Illuminate\Queue\SerializesModels;

class ItemUpdated extends Event
{
    use SerializesModels;

    public $item;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Item $item)
    {
        $this->item = $item;
    }
}
