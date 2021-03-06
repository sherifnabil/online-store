<?php

declare(strict_types=1);

namespace Domains\Fulfillment\Aggregates;

use Domains\Fulfillment\Events\OrderWasCreated;
use Domains\Fulfillment\Events\OrderStateWasUpdated;
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

    public function updateState(int $id, string $state): self
    {
        $this->recordThat(
            domainEvent: new OrderStateWasUpdated(
                id: $id,
                state: $state
            ),
        );

        return $this;
    }
}
