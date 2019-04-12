<?php

namespace App\Listeners;

use App\Events\TaskStatus;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskStatusListener
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
     * @param  TaskStatus  $event
     * @return void
     */
    public function handle(TaskStatus $event)
    {
        //
    }
}
