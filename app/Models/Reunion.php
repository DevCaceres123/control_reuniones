<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reunion extends Model
{
    use HasFactory;
    protected $table = 'reuniones';
    public  function users(){
       

        return $this->belongsToMany('App\Models\User', 'user_reunion', 'reunion_id', 'user_id')
        ->withPivot('id_lector','atraso'); // Incluimos el campo extra (id_lectores)

    }

}
