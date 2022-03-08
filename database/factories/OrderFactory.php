<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Support\Arr;
use Domains\Customer\Models\User;
use Domains\Customer\Models\Location;
use Domains\Fulfillment\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Domains\Fulfillment\States\Statuses\OrderStatus;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $useCoupon = $this->faker->boolean();
        // dd(Location::factory()->create()->id);
        $state = Arr::random(OrderStatus::toLabels());

        return [
            'number'        =>  $this->faker->bothify(
                string: 'ORD-####-####-####'
            ),
            'state'         =>  $state,
            'coupon'        =>  $useCoupon ? $this->faker->imei() : null,
            'total'         =>  $this->faker->numberBetween(10000, 100000),
            'reduction'     =>  $useCoupon ? $this->faker->numberBetween(250, 2500) : 0,
            'user_id'       =>  User::factory()->create(),
            'shipping_id'   =>  Location::factory()->create(),
            'billing_id'    =>  Location::factory()->create(),
            'completed_at'  =>  $this->faker->boolean() ? now() : null,
            'cancelled_at'  =>  (OrderStatus::from($state) === OrderStatus::cancelled()) ? now() : null,
        ];
    }
}
