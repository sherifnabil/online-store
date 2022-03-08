<?php

declare(strict_types=1);

namespace Domains\Fulfillment\Actions;

use Spatie\Enum\Laravel\Enum;
use Domains\Fulfillment\States\Statuses\OrderStatus;
use Domains\Fulfillment\ValueObjects\Stripe\PaymentIntent;

class RetriveOrderStateFromPaymentIntent
{
    public static function handle(PaymentIntent $object): Enum
    {
        return match ($object->status) {
            'succeeded' =>  OrderStatus::completed(),
            'failed'    =>  OrderStatus::declined(),
            'refunded'  =>  OrderStatus::refunded(),
            default     =>  OrderStatus::pending(),
        };
    }
}
