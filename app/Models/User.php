<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'usuario',
        'password',
        'ci',
        'nombres',
        'apellidos',
        'id_persona',
        'estado',
        'email',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function nombres(): Attribute
    {
        return new Attribute(
            //set: fn ($value) => mb_strtoupper($value),
            get: fn($value) => ucwords($value),
        );
    }

    protected function apellidos(): Attribute
    {
        return new Attribute(
            set: fn($value) => mb_strtoupper($value),
            get: fn($value) => mb_strtoupper($value),
        );
    }



    // Relacion eloquent


    public function reuniones()
    {
        //return $this->belongsToMany('App\Models\Reunion');

        return $this->belongsToMany('App\Models\Reunion', 'user_reunion', 'user_id', 'reunion_id')
            ->withPivot('id_lector','atraso'); // Incluimos el campo extra (id_lectores)
    }

    public function pagos()
    {
        return $this->hasMany('App\Models\Pago', 'user_pago');
    }

    public function lectores()
    {
        return $this->hasMany('App\Models\Lector', 'user_id'); // Si user_id es la clave en la tabla lectores
    }

    public function departemento()
    {
        return $this->belongsTo('App\Models\Departamento');
    }

    public function pagos_donacion()
    {
        return $this->hasMany('App\Models\PagoDonacion', 'id_usuario');
    }


    //se creo esta realacion para simplificar las consultas
    public function mesesPagados()
    {
        return $this->belongsToMany(Mes::class, 'pagos', 'id_usuario', 'mes_id'); // Relación a través de pagos
    }

    //pagos con en rol de estudiante

    public function pagosComoEstudiante()
    {
        return $this->hasMany(Pago::class, 'estudiante_id');
    }

     //pagos con en rol de estudiante pero que sea donacion

     public function pagosComoEstudianteDonacion()
     {
         return $this->hasMany(PagoDonacion::class, 'estudiante_id');
     }

}
