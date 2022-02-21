<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert(['nombre' => 'Administrador']);
        DB::table('roles')->insert(['nombre' => 'Tecnico']);
        DB::table('roles')->insert(['nombre' => 'Usuario']);
    }
}
