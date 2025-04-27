<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'eventos';

    protected $fillable = [
        'nome',
        'descricao',
        'data_inicio',
        'data_fim',
        'local',
        'latitude',
        'longitude',
        'capacidade',
        'imagem',
    ];

    public function setLatitudeAttribute($value)
    {
        if ($value < -90 || $value > 90) {
            throw new \InvalidArgumentException('Latitude inválida.');
        }
        $this->attributes['latitude'] = $value;
    }

    public function setLongitudeAttribute($value)
    {
        if ($value < -180 || $value > 180) {
            throw new \InvalidArgumentException('Longitude inválida.');
        }
        $this->attributes['longitude'] = $value;
    }

    public function ingressos()
    {
        return $this->hasMany(Ingresso::class, 'evento_id');
    }
}
