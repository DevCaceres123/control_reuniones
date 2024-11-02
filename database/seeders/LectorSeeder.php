<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lector;
class LectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lector=new Lector();
        $lector->nombre="lector 1";
        $lector->descripcion="este es un lector colo negro";
        $lector->estado="inactivo";
        $lector->uso="inactivo";

        $lector->save();
    }
}
