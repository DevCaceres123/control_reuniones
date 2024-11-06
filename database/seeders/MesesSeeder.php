<?php

namespace Database\Seeders;

use App\Models\Mes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MesesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mes1 = new Mes();
        $mes1->mes = "enero";
        $mes1->save();
        
        $mes2 = new Mes();
        $mes2->mes = "febrero";
        $mes2->save();
        
        $mes3 = new Mes();
        $mes3->mes = "marzo";
        $mes3->save();
        
        $mes4 = new Mes();
        $mes4->mes = "abril";
        $mes4->save();
        
        $mes5 = new Mes();
        $mes5->mes = "mayo";
        $mes5->save();
        
        $mes6 = new Mes();
        $mes6->mes = "junio";
        $mes6->save();
        
        $mes7 = new Mes();
        $mes7->mes = "julio";
        $mes7->save();
        
        $mes8 = new Mes();
        $mes8->mes = "agosto";
        $mes8->save();
        
        $mes9 = new Mes();
        $mes9->mes = "septiembre";
        $mes9->save();
        
        $mes10 = new Mes();
        $mes10->mes = "octubre";
        $mes10->save();
        
        $mes11 = new Mes();
        $mes11->mes = "noviembre";
        $mes11->save();
        
        $mes12 = new Mes();
        $mes12->mes = "diciembre";
        $mes12->save();
        
    }
}
