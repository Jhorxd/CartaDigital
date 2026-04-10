<?php

namespace App\Traits;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        // Añadir el Global Scope automáticamente si el tenant_id está en el contenedor
        static::addGlobalScope('tenant_id', function (Builder $builder) {
            if (app()->has('tenant_id')) {
                $builder->where('tenant_id', app('tenant_id'));
            }
        });

        // Setear automáticamente el tenant_id al crear registros
        static::creating(function ($model) {
            if (app()->has('tenant_id')) {
                $model->tenant_id = app('tenant_id');
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
