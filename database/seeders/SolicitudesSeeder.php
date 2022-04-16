<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SolicitudesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('solicitudes')->insert([
        //     'id_usuario' => 4,
        //     'id_categoria' => 2,
        //     'id_estado' => 1,
        //     'descripcion' => 'Culpa aliqua dolore incididunt pariatur exercitation ullamco.',
        //     'fecha_cita' => Carbon::now()
        // ]);

        // DB::table('solicitudes')->insert([
        //     'id_usuario' => 4,
        //     'id_categoria' => 3,
        //     'id_estado' => 1,
        //     'descripcion' => 'Aparece la pantalla toda borrosa .',
        //     'fecha_cita' => Carbon::now()
        // ]);

        // DB::table('solicitudes')->insert([
        //     'id_usuario' => 4,
        //     'id_categoria' => 1,
        //     'id_estado' => 4,
        //     'id_tecnico' => 1,
        //     'descripcion' => 'La pantalla se ve toda azul.',
        //     'fecha_cita' => Carbon::now()
        // ]);
    }
}
