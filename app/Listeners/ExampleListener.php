<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ExampleEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * @codeCoverageIgnore
 */
class ExampleListener
{
    /**
     * Create the event listener.
     * @return void
     */
    public function __construct()
    {
        //
    }


    /**
     * Handle the event.
     *
     * @param ExampleEvent $event
     *
     * @return void
     */
    public function handle(ExampleEvent $event)
    {
        //
    }
}
