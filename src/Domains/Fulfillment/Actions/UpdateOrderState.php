<?php

declare(strict_types=1);

namespace Domains\Fulfillment\Actions;

use Illuminate\Database\Eloquent\Model;

class UpdateOrderState
{
    public static function handle(Model $order, string $state): void
    {
        $order->update(['state' => $state]);
    }
}
