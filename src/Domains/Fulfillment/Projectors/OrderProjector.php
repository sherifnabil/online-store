<?php

declare(strict_types=1);

namespace Domains\Fulfillment\Projectors;

use Domains\Customer\Factories\OrderFactory;
use Domains\Fulfillment\Actions\CreateOrder;
use Domains\Fulfillment\Events\OrderWasCreated;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class OrderProjector extends Projector
{
    public function onOrderWasCreated(OrderWasCreated $event): void
    {
        $object = OrderFactory::make(
            attributes: [
                'cart'    => $event->cart,
                'billing' => $event->billing,
                'shipping'=> $event->shipping,
                'user'    => $event->user,
                'email'   => $event->email
            ]
        );

        CreateOrder::handle(
            object: $object
        );
    }
}
