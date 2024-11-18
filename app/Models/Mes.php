<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mes extends Model
{
    use HasFactory;
    protected $table = 'meses';


    public function pagos()
    {
        return $this->hasMany('App\Models\Pago', 'mes_id');
    }

    public function pagos_donacion()
    {
        return $this->hasMany('App\Models\PagoDonacion', 'mes_id');
    }
}
