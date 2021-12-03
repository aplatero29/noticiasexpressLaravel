<?php

namespace Database\Factories;

use App\Models\Entrada;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComentarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => rand(1, User::count()),
            'entrada_id' => rand(1, Entrada::count()),
            'asunto' => $this->faker->paragraph(),
            'created_at' => now()
        ];
    }
}
