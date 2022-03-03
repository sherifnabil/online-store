<?php

declare(strict_types=1);

namespace Domains\Customer\ValueObjects;

/**
 * @template CartItemValueObject
 * @template TKey
 * @template TValue
*/
class CartItemValueObject
{
    public function __construct(
        public int $quantity,
        public int $purchasableId,
        public string $purchasableType,
    ) {
    }

    /**
     *
     * @return array<TKey,TValue>
     *
    */
    public function toArray(): array
    {
        return [
            'quantity'  =>  $this->quantity,
            'purchasable_id'  =>  $this->purchasableId,
            'purchasable_type'  =>  $this->purchasableType,
        ];
    }
}
