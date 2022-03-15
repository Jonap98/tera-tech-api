<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EspecialidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('especialidades')->insert(['especialidad' => 'Componentes hardware']);
        DB::table('especialidades')->insert(['especialidad' => 'Equipos Mac']);
        DB::table('especialidades')->insert(['especialidad' => 'Equipos Windows']);
        DB::table('especialidades')->insert(['especialidad' => 'Equipos Android']);
        DB::table('especialidades')->insert(['especialidad' => 'Equipos Iphone']);
    }
}
