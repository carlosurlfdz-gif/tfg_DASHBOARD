<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->truncate();
        DB::table('roles')->insert([
            [
                'id_rol' => 1,
                'nombre' =>'administrador',
            ],
            [
                'id_rol' => 2,
                'nombre' =>'analista_l1',
            ],
            [
                'id_rol' => 3,
                'nombre' =>'analista_l2',
            ],
            [
                'id_rol' => 4,
                'nombre' =>'auditor',
            ],
        ]);
    }
}
