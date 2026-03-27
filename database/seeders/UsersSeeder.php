<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //DB::table('users')->truncate();
        DB::table('users')->insert([
            [
                'id' => 1,
                'id_rol' =>1,
                'nombre'=> 'E. Colombano',
                'username' => 'ecolombano',
                'email' => 'ecolombano@projectscale.lan',
                'password'=> '$2y$10$JUPg0uotrkbTIRZx3aj.6uN6OxZDiMOQVab9lTWP8Isq4NgnrZEES',
                'activo'=> 1,
            ],
            [
                'id' => 2,
                'id_rol' =>1,
                'nombre'=> 'X. Espinosa',
                'username' => 'xespinosa',
                'email' => 'xespinosa@projectscale.lan',
                'password'=> '$2y$10$WO0YviSiht6.zRH2CDJEp.z84FA.D.vJwSK.0ziKP6rQC3FMSmiCu',
                'activo'=> 1,
            ],
            [
                'id' => 3,
                'id_rol' =>1,
                'nombre'=> 'D. Gaya',
                'username' => 'dgaya',
                'email' => 'dgaya@projectscale.lan',
                'password'=> '$2y$10$/QuefCdS857iqIWp/GBJOO7vNC3Za3Jtw3xV4.vgYZsWLJ06QFafm',
                'activo'=> 1,
            ],
            [
                'id' => 4,
                'id_rol' =>1,
                'nombre'=> 'C. Urdiales',
                'username' => 'curdiales',
                'email' => 'curdiales@projectscale.lan',
                'password'=> '$2y$10$D5nh5vDsUR3F4RzNX2/an.SahLyXitymJJ9uiDp1o/wywZdLfVF2q',
                'activo'=> 1,
            ],
        ]);
    }
}
