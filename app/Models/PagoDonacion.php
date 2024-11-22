<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoDonacion extends Model
{
    protected $table='pagos_donacion';
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_usuario');
    }

    public function mes()
    {
        return $this->belongsTo('App\Models\Mes', 'mes_id');
    }

    public function estudiante(){
        return $this->belongsTo('App\Models\User', 'mes_id'); 
    }
}
