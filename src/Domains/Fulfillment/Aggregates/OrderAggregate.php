<?php

declare(strict_types=1);

namespace Domains\Fulfillment\Aggregates;

use Domains\Fulfillment\Events\OrderWasCreated;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class OrderAggregate extends AggregateRoot
{
    public function createOrder(
        string $cart,
        int $billing,
        int $shipping,
        null|int $user,
        null|string $email,
        string $intent
    ): self {
        $this->recordThat(
            domainEvent: new OrderWasCreated(
                cart: $cart,
                billing: $billing,
                shipping: $shipping,
                user: $user,
                email: $email,
                intent: $intent
            ),
        );

        return $this;
    }
}
