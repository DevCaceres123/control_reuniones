<?php

use App\Http\Controllers\Lectores\Controlador_lectores;
use App\Http\Controllers\Pagos\Controlador_cuotas;
use App\Http\Controllers\Pagos\Controlador_pagarCuotas;
use App\Http\Controllers\Pagos\Controlador_pagos;
use App\Http\Controllers\Reuniones\Controlador_asistencia;
use App\Http\Controllers\Reuniones\Controlador_planificacion;
use App\Http\Controllers\Usuario\Controlador_login;
use App\Http\Controllers\Usuario\Controlador_permisos;
use App\Http\Controllers\Usuario\Controlador_rol;
use App\Http\Controllers\Usuario\Controlador_usuario;
use App\Http\Middleware\Autenticados;
use App\Http\Middleware\No_autenticados;
use Mews\Captcha\Facades\Captcha;
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

    // para actualizar el capcha
    Route::get('/cambiar_capcha', function () {

        return response()->json(['captcha' => captcha_src()]);
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
        Route::put('editar_rol/{id_usuario}', 'editar_rol');
    });

    //PARA LOS PERMISOS
    Route::resource('permisos', Controlador_permisos::class);
    Route::post('/permisos/listar', [Controlador_permisos::class, 'listar'])->name('permisos.listar');

    //PARA EL ROL
    Route::resource('roles', Controlador_rol::class);


    // PARA LAS REUNIONES
    Route::controller(Controlador_planificacion::class)->group(function () {

        Route::resource('/reuniones', Controlador_planificacion::class);
        Route::get('/listar_reuniones', 'listar_reuniones');
        Route::get('/lista_asistencia/{id_reunion}', 'lista_asistencia');
        Route::get('/reporte_asistencia/{id_reunion}', 'reporte_asistencia');
        Route::get('/buscar_usuario/{id_reunion}', 'buscar_usuario');
        Route::post('/nueva_asistencia', 'nueva_asistencia')->name('reuniones.nueva_asistencia');
    });

    // PARA LOS PAGOS

    Route::controller(Controlador_pagarCuotas::class)->group(function () {

        Route::resource('/pagarCuotas', Controlador_pagarCuotas::class);
        Route::post('/PagarCuotasDonacion','PagarCuotasDonacion')->name('cuota.pagarDonacion');
    });




    // PARA LOS LECTORES
    Route::controller(Controlador_lectores::class)->group(function () {

        Route::resource('/lectores', Controlador_lectores::class);
        Route::put('/lectores/terminar_uso/{id_lector}', 'terminar_uso');
        Route::put('/lectores/actualizar_lector/{id_lector}', 'actualizar_lector');
    });


    // REPORTES

    // asistencia
    Route::controller(Controlador_asistencia::class)->group(function () {

        Route::resource('/asistencias', Controlador_asistencia::class);
        Route::post('/reporte_asistencia', 'reporte_asistencia')->name('asistencia.reporte_asistencia');
    });

    // cuotas    

    Route::controller(Controlador_cuotas::class)->group(function () {

        Route::resource('/cuotas', Controlador_cuotas::class);
        Route::get('/cuotas_reporte_anual', 'cuotas_reporte_anual')->name('cuotas.final');
    });
});
