<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lector extends Model
{
    use HasFactory;
    protected $table = 'lectores';
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id'); // Usar el nombre exacto del campo en la tabla 'lectores'
    }
}
