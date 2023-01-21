<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('academico/contabilidad')->group(function() {
    // comenzando desde: Modules\Contabilidad\Http
    
    //Route::post('/mail_cuentas_preparar', 'Back\Controllers\MailCuentasPrepararController@fromRedirect')
    //        ->withoutMiddleware( [\App\Http\Middleware\VerifyCsrfToken::class] )
    //        ->name('contabilidad.back.MailCuentasPrepararDesdeRedirect');
    
    Route::post('/mail_cuentas_preparar', 'Back\Controllers\MailCuentasPrepararController@mostrarTextos')
            ->withoutMiddleware( [\App\Http\Middleware\VerifyCsrfToken::class] )
            ->name('contabilidad.back.MailCuentasPreparar');
    
    Route::post('/mail_cuentas_guardar_textos', 'Back\Controllers\MailCuentasPrepararController@guardarTextos')
            ->withoutMiddleware( [\App\Http\Middleware\VerifyCsrfToken::class] )
            ->name('contabilidad.back.MailCuentasGuardarTextos');
    
    Route::get('/mail_cuentas_seleccionar', 'Back\Controllers\MailCuentasSeleccionarController@index')
            //->withoutMiddleware( [\App\Http\Middleware\VerifyCsrfToken::class] )
            ->name('contabilidad.back.MailCuentasSeleccionar');
    
    Route::post('/mail_cuentas_enviar', 'Back\Controllers\MailCuentasEnviarController@index')
            ->name('contabilidad.back.MailCuentasEnviar');
    
    // 
    Route::post('/rendicion_consultar_fechas', 'Back\Controllers\RendicionConsultarFechasController@index')
            ->withoutMiddleware( [\App\Http\Middleware\VerifyCsrfToken::class] )
            ->name('contabilidad.back.RendicionConsultarFechas');
    
    Route::post('/rendicion_ultima', 'Back\Controllers\RendicionConsultarFechasController@ultimaRendicion')
            ->withoutMiddleware( [\App\Http\Middleware\VerifyCsrfToken::class] )
            ->name('contabilidad.back.RendicionVerPagos');
    
});
