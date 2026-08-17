<?php

namespace App\Models;

use App\Models\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visit extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'distributor_id',
        'shop_id',
        'latitude',
        'longitude',
        'distance_meters',
        'visited_at',
    ];

    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
        ];
    }

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'distributor_id');
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}