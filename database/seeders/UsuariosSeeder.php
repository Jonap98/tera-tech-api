<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usuarios')->insert([
            'id_rol' => 1,
            'nombre' => 'Medaly',
            'apellido' => 'Guerrero',
            'correo' => 'medaly@gmail.com',
            'password' => Hash::make('password'),
        ]);

        DB::table('usuarios')->insert([
            'id_rol' => 2,
            'nombre' => 'Marijose',
            'apellido' => 'Gaona',
            'correo' => 'marijose@gmail.com',
            'password' => Hash::make('password'),
        ]);

        DB::table('users')->insert([
            'id_rol' => 1,
            'name' => 'Medaly',
            'last_name' => 'Guerrero',
            'email' => 'medaly@gmail.com',
            'password' => Hash::make('password'),
        ]);

        DB::table('users')->insert([
            'id_rol' => 2,
            'name' => 'Marijose',
            'last_name' => 'Gaona',
            'email' => 'marijose@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}
