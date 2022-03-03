<?php

declare(strict_types=1);

namespace Domains\Fulfillment\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class OrderWasCreated extends ShouldBeStored
{
    public function __construct(
        public string $cart,
        public int $billing,
        public int $shipping,
        public null|int $user,
        public null|string $email
    ) {
    }
}
