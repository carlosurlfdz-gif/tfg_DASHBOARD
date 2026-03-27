<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 
class LogAccesoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('log_acceso')->truncate();
        DB::table('log_acceso')->insert([
            [
                'id_usuario' => 1,
                
                'username_intentado' =>'curdiales',
                'ip_origen'=> '192.168.10.10',
                'resultado' => 1,
                'fecha_acceso' => now()
            ]
        ]);
    }
}
