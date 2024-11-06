<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    public function users()
    {
        return $this->belongsToMany('App\Models\User','user_pago');
    }

    public function mes(){
        return $this->belongsTo('App\Models\Mes', 'mes_id'); 
    }
}

  