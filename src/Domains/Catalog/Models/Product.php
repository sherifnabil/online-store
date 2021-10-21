<?php

declare(strict_types=1);

namespace Domains\Catalog\Models;

use Database\Factories\ProductFactory;
use Domains\Catalog\Models\Builder\ProductBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use JustSteveKing\KeyFactory\Models\Concerns\HasKey;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasKey;
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'description',
        'cost',
        'retail',
        'category_id',
        'range_id',
        'active',
        'vat',
    ];

    protected $casts = [
        'vat' => 'boolean',
        'active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            related: Category::class,
            foreignKey:'category_id'
        );
    }

    public function range(): BelongsTo
    {
        return $this->belongsTo(
            related: Range::class,
            foreignKey:'range_id'
        );
    }

    protected static function newFactory(): Factory
    {
        return new ProductFactory();
    }

    public function newEloquentBuilder($query): Builder
    {
        return  new ProductBuilder(
            query: $query
        );
    }
}
