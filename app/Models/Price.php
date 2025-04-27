<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = ['ingresso_id', 'unit_amount', 'active', 'price_id'];

    public function ingresso()
    {
        return $this->belongsTo(Ingresso::class, 'ingresso_id');
    }
}
