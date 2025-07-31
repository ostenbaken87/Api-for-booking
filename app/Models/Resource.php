<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\ResourceType;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'description'];

    protected $casts = [
        'type' => ResourceType::class,
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
