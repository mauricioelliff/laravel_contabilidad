<?php
/*
 * Encola los mails que serán procesados por el job que envía mails.
 */

namespace Modules\Contabilidad\Http\Back\Controllers;


//use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
//use Illuminate\Routing\Controller;


use App\Http\Controllers\EsteProyectoController;

use Modules\Contabilidad\Usecases\Deudas\InfoDeudaEstudiante;
use Modules\Contabilidad\Usecases\Mails\EnviarDeudaEstudiante;


class MailCuentasEnviarController extends EsteProyectoController
{
    public function __construct( Request $request ) {
        parent::__construct( $request );
    }
    
    /* 
     * POST
     * 
     * INPUT
     * POST_checkeados  <json array> dnis seleccionados
     * sedes_id         <int>
     * 
     */
    public function index( Request $request )
    {
        if( !$arrayOk=$this->limpiarDatosRequest( $request ) ){
            return 'Hay algún problema en el requerimiento';
            // return $this->json( $respuestas ); // EN REALIDAD AQUI ESPERA UN HTML
        }
        
        $info = null;
        $seleccionados = json_decode($arrayOk['POST_checkeados'],true);
        $sedes_id = $arrayOk['sedes_id'];
        $enviarDeudas = ($arrayOk['enviarDeudas']=='1')? true : false;
        if( count($seleccionados)>0 ){
            $EnviarDeudaEstudiante = new EnviarDeudaEstudiante();
            $info = $EnviarDeudaEstudiante->__invoke(  $seleccionados, $sedes_id, $enviarDeudas  );
        }
        
        return view('contabilidad::back.mails.mail_estudiante_deuda_post_envio')->with(['info'=>$info]);
    }
}
