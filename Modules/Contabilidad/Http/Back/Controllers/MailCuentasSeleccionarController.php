<?php

namespace Modules\Contabilidad\Http\Back\Controllers;

/*
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
 * 
 */

use App\Http\Controllers\EsteProyectoController;
use Illuminate\Http\Request;

use Modules\Contabilidad\Usecases\Deudas\InfoDeudaEstudiante;

class MailCuentasSeleccionarController extends EsteProyectoController
{
    
    public function __construct( Request $request ) {
        parent::__construct( $request );
    }
    
    public function index( Request $request )
    {        
        // no llegan parámetros. Igualmente por sí, busco valores:
        $arrayOk=$this->limpiarDatosRequest( $request );
        //if( !$arrayOk=$this->limpiarDatosRequest( $request ) ){
        //    return 'Hay algún problema en el requerimiento';
        //    // return $this->json( $respuestas ); 
        //}
        
        // Si llego aquí desde un redirect, tomo los parámetros desde session
        $fromSessionValues = $request->session()->all();
        $arrayOk = $arrayOk + $fromSessionValues;
        
        $sedes_id = ( isset($arrayOk['sedes_id']) )? $arrayOk['sedes_id'] : (( isset($_SESSION['sedes_id']) )? $_SESSION['sedes_id'] : SEDE_CENTRAL_ID );
        $anio = ( isset($arrayOk['anio']) )? $arrayOk['anio'] : date('Y');
        
        
        $InfoDeudaEstudiante = new InfoDeudaEstudiante();
        $info = $InfoDeudaEstudiante->enLaSede( $sedes_id, $anio );
        $info['ruta_guardar'] = 'contabilidad.back.MailCuentasEnviar';
        $info['enviarDeudas'] = ( key_exists('enviarDeudas',$arrayOk) && $arrayOk['enviarDeudas'] )? true : false;

        /*
         * $info <array>
         *      'Sede'      => <object Sede>
         *      'estudiantes' => <array objetos Alumno>
         *      'deudas'    => array(179) { 
         *                          [21495380]=> array(2) { 
         *                              [0]=> array(10) { 
         *                                       "dni" => "21495380"
         *                                       "id" => 6289
         *                                       "descripcion" => "Profesorado de Yoga Curso 1"
         *                                       "descripcion_corta" => "Nivel 1, Cuota 4, 2021"
         *                                       "monto_deuda" => 4200
         *                                       "sedes_cursosxanio_id" => 590
         *                                       "cursos_id" => 3
         *                                       "nombre_humano" => "Profesorado Natha Yoga Nivel 1"
         *                                       "ev_abreviatura" => "CU4"
         *                                       "evscxa_id" => 6289
         */
        
        return view('contabilidad::back.mails.mail_estudiante_deuda_seleccionar_estudiantes', ['info'=>$info] );
    }
}
