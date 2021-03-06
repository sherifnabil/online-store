<?php

declare(strict_types=1);

namespace Database\Factories;

use Domains\Catalog\Models\Range;
use Illuminate\Database\Eloquent\Factories\Factory;

class RangeFactory extends Factory
{
    protected $model = Range::class;

    public function definition(): array
    {
        return [
            'name'  => $this->faker->words(nb:3, asText:true),
            'description' => $this->faker->paragraphs(nb:4, asText:true),
            'active' => true
        ];
    }
}
