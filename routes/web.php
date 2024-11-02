<?php

use App\Http\Controllers\Lectores\Controlador_lectores;
use App\Http\Controllers\Pagos\Controlador_pagos;
use App\Http\Controllers\Reuniones\Controlador_reuniones;
use App\Http\Controllers\Usuario\Controlador_login;
use App\Http\Controllers\Usuario\Controlador_permisos;
use App\Http\Controllers\Usuario\Controlador_rol;
use App\Http\Controllers\Usuario\Controlador_usuario;
use App\Http\Middleware\Autenticados;
use App\Http\Middleware\No_autenticados;
use Illuminate\Support\Facades\Route;



Route::prefix('/')->middleware([No_autenticados::class])->group(function () {
    Route::get('/', function () {
        return view('login');
    })->name('login');

    Route::get('/login', function () {
        return view('login', ['fromHome' => true]);
    })->name('login_home');

    Route::controller(Controlador_login::class)->group(function () {
        Route::post('ingresar', 'ingresar')->name('log_ingresar');
    });
});


Route::prefix('/admin')->middleware([Autenticados::class])->group(function () {
    Route::controller(Controlador_login::class)->group(function () {
        Route::get('inicio', 'inicio')->name('inicio');
        Route::post('cerrar_session', 'cerrar_session')->name('salir');
    });

    // PARA EL USUARIO
    Route::controller(Controlador_usuario::class)->group(function () {
        Route::get('perfil', 'perfil')->name('perfil');
        Route::get('listarUsuarios', 'listar');
        Route::post('asignar_targeta', 'asignar_targeta');
        Route::post('pwd_guardar', 'password_guardar')->name('pwd_guardar');
        Route::resource('/usuarios', Controlador_usuario::class);
        Route::put('resetar_usuario/{id_usuario}', 'resetar_usuario');
    });

    //PARA LOS PERMISOS
    Route::resource('permisos', Controlador_permisos::class);
    Route::post('/permisos/listar', [Controlador_permisos::class, 'listar'])->name('permisos.listar');

    //PARA EL ROL
    Route::resource('roles', Controlador_rol::class);


    // PARA LAS REUNIONES
    Route::controller(Controlador_reuniones::class)->group(function () {

        Route::resource('/reuniones', Controlador_reuniones::class);
        Route::get('/listar_reuniones', 'listar_reuniones');
        Route::get('/lista_asistencia/{id_reunion}', 'lista_asistencia');
    });

    // PARA LOS PAGOS
    Route::controller(Controlador_pagos::class)->group(function () {

        Route::resource('/pagos', Controlador_pagos::class);
    });

    // PARA LOS LECTORES
    Route::controller(Controlador_lectores::class)->group(function () {

        Route::resource('/lectores', Controlador_lectores::class);
        Route::put('/lectores/terminar_uso/{id_lector}', 'terminar_uso');
        Route::put('/lectores/actualizar_lector/{id_lector}', 'actualizar_lector');

    });
});
