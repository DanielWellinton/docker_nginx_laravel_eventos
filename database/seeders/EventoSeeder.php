<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Evento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('eventos')->truncate(); // Limpa a tabela antes de inserir novos dados

        for ($i = 1; $i <= 20; $i++) {
            Evento::create([
                'nome' => "Evento $i",
                'descricao' => "Descrição do Evento $i",
                'data_inicio' => Carbon::now()->addDays($i)->format('Y-m-d H:i:s'),
                'data_fim' => Carbon::now()->addDays($i + 1)->format('Y-m-d H:i:s'),
                'local' => "Cidade " . Str::random(5),
                'capacidade' => rand(50, 500),
                'latitude' => mt_rand(-9000000, 9000000) / 100000,
                'longitude' => mt_rand(-18000000, 18000000) / 100000,
            ]);
        }
    }
}
