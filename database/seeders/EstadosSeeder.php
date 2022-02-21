<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('estados')->insert(['estado' => 'Sin resolver']);
        DB::table('estados')->insert(['estado' => 'Abierto']);
        DB::table('estados')->insert(['estado' => 'Asignado']);
        DB::table('estados')->insert(['estado' => 'En espera']);
        DB::table('estados')->insert(['estado' => 'Detalle']);
        DB::table('estados')->insert(['estado' => 'Listo']);
    }
}
