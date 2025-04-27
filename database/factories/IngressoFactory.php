<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Evento;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ingresso>
 */
class IngressoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $evento = Evento::inRandomOrder()->first() ?? Evento::factory()->create();
        $quantidadeMaxima = $evento->capacidade;
        return [
            'name' => fake()->name(),
            'evento_id' => Evento::inRandomOrder()->first()->id ?? Evento::factory(),
            'unit_amount' => $this->faker->randomFloat(2, 50, 300),
            'quantity' => $this->faker->numberBetween(1, $quantidadeMaxima),
            'active' => $this->faker->boolean(50),
        ];
    }
}
