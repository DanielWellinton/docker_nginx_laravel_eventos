<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Evento;
use App\Models\Ingresso;

class IngressoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Evento::count() === 0) {
            $this->call(EventoSeeder::class);
        }

        Ingresso::factory(50)->create();
    }
}
