<?php
/*
 * Muestra una view con el texto que se enviará a los estudiantes con cuentas pendientes
 */

namespace Modules\Contabilidad\Http\Back\Controllers;

/*
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
 * 
 */

use App\Http\Controllers\EsteProyectoController;
use Illuminate\Http\Request;

use Modules\Contabilidad\Usecases\Mails\DataTextoEstudianteDeuda;

class MailCuentasPrepararController extends EsteProyectoController
{
    
    public function __construct( Request $request ) {
        parent::__construct( $request );
    }
    
    /* Este action fue una idea para recibir las solicitudes Zend 
     * a un controller embudo Laravel que luego derivaría a estos controllers
    public function fromRedirect( Request $request )
    {
        $fromSessionValues = $request->session()->all();
        dd('$fromSessionValues',$fromSessionValues);
        return $this->index( $fromSessionValues );
    }
     */
    
    /*
     * INPUT
     * <POST> key 'POST_json' => json con values
     */
    public function mostrarTextos( Request $request )
    {
        phpinfo();
        return;
        // foreach ($request->except('_token') as $key => $part) {
        // $all = $request->all();
        // 
        //if( !$arrayOk=$this->limpiarDatosRequest( $request ) ){
        if( !$arrayOk=$this->limpiarDatos( json_decode( $request->POST_json, true ) ) ){
            return 'Hay algún problema en el requerimiento';
            // return $this->json( $respuestas ); // NE REALIDAD AQUI ESPERA UN HTML
        }

        
        $sedes_id = ( isset($arrayOk['sedes_id']) )? $arrayOk['sedes_id'] : 
                        ( ( isset($arrayOk['sede']) )? $arrayOk['sede'] :   
                            (( isset($_SESSION['sedes_id']) )? $_SESSION['sedes_id'] : SEDE_CENTRAL_ID ) 
                        );

        $DataTextoEstudianteDeuda = new DataTextoEstudianteDeuda();        

        $data = $DataTextoEstudianteDeuda->__invoke( $sedes_id );      
        // data es un array con keys 'sedes_id', 'MailTexto'
        $data['ruta_guardar'] = 'contabilidad.back.MailCuentasGuardarTextos';
        // $data [ 'Sede'=> , 'MailTexto_col'=> , 'ruta_guardar'=> )

        return view('contabilidad::back.mails.mail_estudiante_deuda_preparar_texto', ['data'=>$data] );
    }
    
    // Además de los campos de texto, llega sedes_id y motivo.
    public function guardarTextos( Request $request )
    {
        if( !$arrayOk=$this->limpiarDatosRequest( $request ) ){
            return 'Hay algún problema en el requerimiento';
            // return $this->json( $respuestas ); // en realidad aqui espera un html
        }
        $useCaseGuardarTexto = new \Modules\Contabilidad\Usecases\Mails\GuardarMailTextoModelo();
        $useCaseGuardarTexto->__invoke( $arrayOk );
        
        // $request->setMethod('POST'); no funca. La ruta destino la sigue tomando como GET
        // voy a optar por cambiar la definición de la ruta a GET.
        $newRoute = 'contabilidad.back.MailCuentasSeleccionar';
        // $arrayOk['enviarDeudas'] valdrá "on" si fue clickeado
        return redirect()->route( $newRoute )->with( ['sedes_id'=>$arrayOk['sedes_id'], 'enviarDeudas'=>(isset($arrayOk['enviarDeudas'])) ] ); 
        //with deja las variables en Session, no en Request
    }
}
