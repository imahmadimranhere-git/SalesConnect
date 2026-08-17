<?php

namespace App\Models;

use App\Models\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopAssignment extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'distributor_id',
        'shop_id',
        'day_of_week',
        'visit_order',
    ];

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'distributor_id');
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}