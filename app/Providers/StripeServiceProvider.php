<?php

declare(strict_types=1);

namespace App\Providers;

use Stripe\Stripe;
use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\Stripe\SignatureValidationMiddleware;

class StripeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Stripe::setApiKey(
            apiKey: config('services.stripe.key')
        );
    }
}
