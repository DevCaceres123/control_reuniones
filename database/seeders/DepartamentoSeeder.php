<?php

namespace Database\Seeders;

use App\Models\Departamento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $departamentos = [
            ['expedido' => 'lp', 'departamento' => 'La Paz'],
            ['expedido' => 'pt', 'departamento' => 'Potosí'],
            ['expedido' => 'cb', 'departamento' => 'Cochabamba'],
            ['expedido' => 'sc', 'departamento' => 'Santa Cruz'],
            ['expedido' => 'or', 'departamento' => 'Oruro'],
            ['expedido' => 'ch', 'departamento' => 'Chuquisaca'],
            ['expedido' => 'tj', 'departamento' => 'Tarija'],
            ['expedido' => 'be', 'departamento' => 'Beni'],
            ['expedido' => 'pd', 'departamento' => 'Pando']
        ];

        foreach ($departamentos as $data) {
            $departamento = new Departamento();
            $departamento->expedido = $data['expedido'];
            $departamento->departamento = $data['departamento'];
            $departamento->save();
        }
    }
}