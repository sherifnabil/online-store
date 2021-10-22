<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Domains\Catalog\Models\Product;
use Domains\Catalog\Models\Variant;
use Domains\Customer\Models\Address;
use Domains\Customer\Models\Cart;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Address::factory()->create();
        // Category::factory(10)->create();
        // Range::factory(10)->create();
        // Product::factory(50)->create();
        Variant::factory(50)->create();
        Cart::factory(10)->create();
    }
}
