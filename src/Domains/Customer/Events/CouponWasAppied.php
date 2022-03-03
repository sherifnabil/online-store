<?php

declare(strict_types=1);

namespace Domains\Customer\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class CouponWasAppied extends ShouldBeStored
{
    public function __construct(
        public int $cartID,
        public string $code,
    ) {
    }
}
