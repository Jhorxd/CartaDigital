<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'title',
        'description',
        'amount',
        'date',
        'category',
    ];

    protected $casts = [
        'date'   => 'date',
        'amount' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public static function predefinedCategories(): array
    {
        return [
            'Ventas',
            'Servicios',
            'Comisiones',
            'Alquiler',
            'Inversiones',
            'Transferencias',
            'Bonos',
            'Otros',
        ];
    }
}
