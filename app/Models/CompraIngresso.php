<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompraIngresso extends Model
{
    /** @use HasFactory<\Database\Factories\CompraIngressoFactory> */
    use HasFactory;

    protected $table = 'compra_ingressos';

    protected $fillable = [
        'ingresso_id',
        'user_id',
    ];

    public function ingresso()
    {
        return $this->belongsTo(Ingresso::class, 'ingresso_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
