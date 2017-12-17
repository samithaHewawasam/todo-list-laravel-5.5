<?php

namespace App\Listeners;

use App\Events\CurdEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class CurdListner
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
     * @param  CurdEvent  $event
     * @return void
     */
    public function handle(CurdEvent $event)
    {
        if($event->crud == 'create')
        Log::debug('Task '.$event->task->name.' has been created');

        if($event->crud == 'delete')
        Log::debug('Task: '.$event->task->name.' has been deleted');

        if($event->crud == 'edit')
        Log::debug('Task: '.$event->task->name.' has been edited successfully');

        if($event->crud == 'complete')
        Log::debug('Task: '.$event->task->name. ' has been marked as done');
    }
}
