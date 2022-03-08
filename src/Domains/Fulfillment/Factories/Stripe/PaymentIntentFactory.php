<?php

declare(strict_types=1);

namespace Domains\Fulfillment\Factories\Stripe;

use Stripe\Event;
use Domains\Fulfillment\ValueObjects\Stripe\PaymentIntent;

class PaymentIntentFactory
{
    public static function make(Event $event): PaymentIntent
    {
        return new PaymentIntent(
            id:            $event->data->object->id,
            object:        $event->data->object->object,
            amount:        $event->data->object->amount,
            currency:      $event->data->object->currency,
            description:   $event->data->object->description,
            status:        $event->data->object->status,
        );
    }
}
