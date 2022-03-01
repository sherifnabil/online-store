<?php

declare(strict_types=1);

namespace Domains\Fulfillment\ValueObjects;

class OrderValueObject
{
    public function __construct(
        public string $cart,
        public int $billing,
        public int $shipping,
        public null|int $user,
        public null|string $email
    ) {
    }

    /**
     *
    */
    public function toArray(): array
    {
        return [
            'cart'      => $this->cart,
            'billing'   => $this->billing,
            'shipping'  => $this->shipping,
            'user'      => $this->user,
            'email'     => $this->email
        ];
    }
}
