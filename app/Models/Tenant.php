<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subdomain',
        'logo',
        'address',
        'whatsapp',
        'schedule',
        'is_active',
    ];

    public function owner()
    {
        return $this->hasOne(User::class);
    }
}
