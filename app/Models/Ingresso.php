<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingresso extends Model
{
    use HasFactory;

    protected $fillable = ['evento_id', 'name', 'unit_amount', 'quantity', 'active', 'product_id'];


    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function compras()
    {
        return $this->hasMany(CompraIngresso::class);
    }

    public function precos()
    {
        return $this->hasMany(Price::class);
    }

    public function priceAtivo()
    {
        return $this->hasOne(Price::class)->where('active', true)->latest();
    }
}
