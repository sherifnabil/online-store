<?php

declare(strict_types=1);

namespace Database\Seeders;

use Domains\Customer\Models\Address;
use Illuminate\Database\Seeder;
use Domains\Customer\Models\Location;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Address::factory()->create();
    }
}
