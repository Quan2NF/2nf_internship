<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\ProjectCreated;
use App\Listeners\SendProjectCreatedNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ProjectCreated::class => [
            SendProjectCreatedNotification::class,
        ],
    ];
}