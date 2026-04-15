<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToTenant;

class Product extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'category_id',
        'name',
        'brand',
        'description',
        'price',
        'old_price',
        'sizes',
        'gallery',
        'badge',
        'image',
        'is_active',
        'order_position',
    ];

    protected $casts = [
        'sizes' => 'array',
        'gallery' => 'array',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
