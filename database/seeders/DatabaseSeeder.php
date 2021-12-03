<?php

namespace Database\Seeders;

use App\Models\Entrada;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();
        \App\Models\Categoria::factory(5)->create();
        //Primero creamos Usuarios y Categorias por que Entrada necesita sus IDs
        \App\Models\Entrada::factory(20)->create();
        //Por último creamos los Comentarios usando los IDs de Usuarios y Entradas
        //TODO: Hacer seeding de Comentarios
        /*         ComentarioFactory::create(); */
        /*         $entradas = Entrada::all();
        $entradas->comentarios()->attach($); */
/*         $faker = Factory::create();

        $entradas = Entrada::lists('id');
        $ultimo = count($entradas) - 1;

        for ($i=0; $i < 5; $i++) { 
            $usuarios = User::create([
                'entrada_id' => $
            ]);
        } */
    }
}
