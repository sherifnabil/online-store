<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Domains\Customer\Projectors\CartProjector;
use Spatie\EventSourcing\Facades\Projectionist;
use Domains\Fulfillment\Projectors\OrderProjector;

class EventSourcingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Projectionist::addProjectors([
            CartProjector::class,
            OrderProjector::class,
        ]);
    }

    public function boot(): void
    {
        //
    }
}
