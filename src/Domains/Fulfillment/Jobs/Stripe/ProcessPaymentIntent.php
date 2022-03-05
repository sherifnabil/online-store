<?php

declare(strict_types=1);

namespace Domains\Fulfillment\Jobs\Stripe;

use Illuminate\Bus\Queueable;
use Domains\Fulfillment\Models\Order;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Domains\Fulfillment\Aggregates\OrderAggregate;
use Domains\Fulfillment\States\Statuses\OrderStatus;
use Domains\Fulfillment\ValueObjects\Stripe\PaymentIntent;
use Domains\Fulfillment\Actions\RetriveOrderStateFromPaymentIntent;

class ProcessPaymentIntent implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use SerializesModels;
    use InteractsWithQueue;

    public function __construct(
        public PaymentIntent $object
    ) {
    }


    public function handle(): void
    {
        // look up an order by the intent id based of the object id
        $order = Order::query()->where('intent_id', $this->object->id)->first();

        $status = RetriveOrderStateFromPaymentIntent::handle($this->object);

        // Using the Order Aggregate retrive based on the order uuid, and call the updateOrderStatus method
        OrderAggregate::retrieve(
            uuid: $order->uuid
        )->updateState(
            id: $order->id,
            state: $status->value,
        )->persist();
    }
}
