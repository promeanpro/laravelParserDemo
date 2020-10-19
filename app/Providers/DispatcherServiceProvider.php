<?php

namespace App\Providers;

use Illuminate\Bus\BusServiceProvider;
use Illuminate\Bus\Dispatcher;
use Illuminate\Contracts\Bus\Dispatcher as DispatcherContract;
use Illuminate\Contracts\Bus\QueueingDispatcher as QueueingDispatcherContract;

class DispatcherServiceProvider extends BusServiceProvider
{
    public function register() : void
    {
        $this->app->alias(
            Dispatcher::class, DispatcherContract::class
        );

        $this->app->alias(
            Dispatcher::class, QueueingDispatcherContract::class
        );
    }
}
