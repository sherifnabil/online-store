<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Support\Arr;
use Domains\Customer\Models\Cart;
use Domains\Customer\Models\Coupon;
use Domains\Customer\Models\User;
use Domains\Customer\States\Statuses\CartStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        return [
            'status'    => Arr::random(
                array: CartStatus::toLabels()
            ),
            'coupon'    => null,
            'total'     => $this->faker->numberBetween(10000, 100000),
            'reduction' => 0,
            'user_id'   => User::factory()->create(),
        ];
    }

    public function withCoupon(): Factory
    {
        $coupon = Coupon::factory()->create();

        return $this->state(function (array $attributes) use ($coupon): array {
            return [
                'couopn'    =>  $coupon->code,
                'reduction' =>  $coupon->reduction,
            ];
        });
    }
}
