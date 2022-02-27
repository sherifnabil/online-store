<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Domains\Customer\Models\Cart;
use Domains\Customer\Models\Order;
use Domains\Catalog\Models\Variant;
use Domains\Customer\Models\Coupon;
use Domains\Customer\Models\Address;
use Domains\Customer\Models\OrderLine;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Address::factory()->create();
        // Category::factory(10)->create();
        // Range::factory(10)->create();
        // Product::factory(50)->create();
        // Variant::factory(50)->create();
        Cart::factory(10)->create();
        // OrderLine::factory(20)->create();
        Coupon::factory(20)->create();
    }
}
