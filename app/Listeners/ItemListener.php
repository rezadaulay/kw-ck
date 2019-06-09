<?php

namespace App\Listeners;

use App\Events\ItemUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Checklist;
use App\Item;

class ItemListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ItemUpdated  $event
     * @return void
     */
    public function handle(ItemUpdated $event)
    {
        // if ($event->item->getOriginal('is_completed') != $event->item->is_completed) {
            $checklist = Checklist::find($event->item->checklist_id);
            // \Log::error();
            if (!Item::where('checklist_id', $event->item->checklist_id)->where('is_completed', 0)->count()) {
                $checklist->update([
                    'is_completed' => 1,
                    'completed_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                $checklist->update([
                    'is_completed' => 0,
                    'completed_at' => null
                ]);
            }
        // }
    }
}
