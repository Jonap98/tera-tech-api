<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categorias')->insert(['nombre' => 'Mantenimiento general']);
        DB::table('categorias')->insert(['nombre' => 'Formateo']);
        DB::table('categorias')->insert(['nombre' => 'Instalar programas']);
        DB::table('categorias')->insert(['nombre' => 'Cambiar piezas']);
        DB::table('categorias')->insert(['nombre' => 'Reparación']);
        DB::table('categorias')->insert(['nombre' => 'Otra']);
    }
}
