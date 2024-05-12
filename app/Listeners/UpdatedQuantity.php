<?php

namespace App\Listeners;

use App\Models\Book;
use App\Events\UpdateQuantityAction;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdatedQuantity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UpdateQuantityAction $event): void
    {
        //
        $quantity = $event->book->quantity;
        if ($quantity > 0) {
            $event->book->update([
                'quantity' => $quantity - 1,
            ]);
        }


        $borrowStatuses = $event->borrow;
    foreach ($borrowStatuses as $status) {
        if ($status == "Borrowed") {
            $event->book->update([
                'quantity' => $quantity + 1,
            ]);
        }

        }
    }
}


