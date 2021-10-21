<?php

declare(strict_types=1);

namespace Database\Factories;

use Domains\Catalog\Models\Category;
use Domains\Catalog\Models\Product;
use Domains\Catalog\Models\Range;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $cost = $this->faker->numberBetween(100, 10000);
        $retail = ($cost * config('shop.profit_margin'));

        return [
            'name'          =>  $this->faker->words(nb:4, asText:true),
            'description'   => $this->faker->paragraphs(nb:4, asText:true),
            'cost'          =>  $cost,
            'retail'        =>  $retail,
            'active'        =>  $this->faker->boolean(),
            'vat'           =>  config('shop.vat'),
            'category_id'   =>  Category::factory()->create(),
            'range_id'      =>  $this->faker->boolean ? Range::factory()->create(): null,
        ];
    }
}
