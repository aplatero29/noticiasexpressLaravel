<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntradaFactory extends Factory
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
            'categoria_id' => rand(1, Categoria::count()),
            'titulo' => $this->faker->sentence(),
            'imagen' => $this->faker->imageUrl(),
            'descripcion' => $this->faker->text(),
            'created_at' => now()
        ];
    }
}
