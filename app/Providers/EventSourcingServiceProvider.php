<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Domains\Customer\Projectors\CartProjector;
use Domains\Customer\Projectors\OrderProjector;
use Spatie\EventSourcing\Facades\Projectionist;

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
