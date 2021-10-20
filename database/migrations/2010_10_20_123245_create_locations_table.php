<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('house'); // 1234
            $table->string('street'); // street name
            $table->string('parish')->nullable(); // some village
            $table->string('ward')->nullable(); // town
            $table->string('district')->nullable(); // Greater area
            $table->string('country');
            $table->string('postcode'); // DEF56
            $table->string('county')->nullable(); // Derby County
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
