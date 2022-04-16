<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('usuarios')->insert([
        //     'id_rol' => 1,
        //     'id_especialidad' => null,
        //     'nombre' => 'Medaly',
        //     'apellido' => 'Guerrero',
        //     'correo' => 'medaly@gmail.com',
        //     'password' => Hash::make('123456'),
        // ]);

        // DB::table('usuarios')->insert([
        //     'id_rol' => 2,
        //     'id_especialidad' => 3,
        //     'nombre' => 'Marijose',
        //     'apellido' => 'Gaona',
        //     'correo' => 'marijose@gmail.com',
        //     'password' => Hash::make('123456'),
        // ]);

        DB::table('users')->insert([
            'id_rol' => 1,
            'id_especialidad' => null,
            'name' => 'Medaly',
            'last_name' => 'Guerrero',
            'email' => 'medaly@gmail.com',
            'password' => Hash::make('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('users')->insert([
            'id_rol' => 2,
            'id_especialidad' => 1,
            'name' => 'Marijose',
            'last_name' => 'Gaona',
            'email' => 'marijose@gmail.com',
            'password' => Hash::make('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('users')->insert([
            'id_rol' => 2,
            'id_especialidad' => 2,
            'name' => 'Techico 2',
            'last_name' => 'Dos',
            'email' => 'tech2@gmail.com',
            'password' => Hash::make('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('users')->insert([
            'id_rol' => 2,
            'id_especialidad' => 3,
            'name' => 'Techico 3',
            'last_name' => 'Tres',
            'email' => 'tech3@gmail.com',
            'password' => Hash::make('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('users')->insert([
            'id_rol' => 2,
            'id_especialidad' => 4,
            'name' => 'Techico 4',
            'last_name' => 'Cuatro',
            'email' => 'tech4@gmail.com',
            'password' => Hash::make('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('users')->insert([
            'id_rol' => 2,
            'id_especialidad' => 5,
            'name' => 'Techico 5',
            'last_name' => 'Cinco',
            'email' => 'tech5@gmail.com',
            'password' => Hash::make('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('users')->insert([
            'id_rol' => 3,
            'id_especialidad' => null,
            'name' => 'Elliot',
            'last_name' => 'Alderson',
            'email' => 'robot@gmail.com',
            'password' => Hash::make('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
