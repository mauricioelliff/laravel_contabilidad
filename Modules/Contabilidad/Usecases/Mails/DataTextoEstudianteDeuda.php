<?php
/*
 * Devuelve la row con los textos de deudas, de la sede buscada
 * 
 */

namespace Modules\Contabilidad\Usecases\Mails;

use Modules\General\Models\DB\MailTextoModelo;
/**
 * Description of PreEnviarDeudas
 *
 * @author mauricio
 */
class DataTextoEstudianteDeuda 
{
    public static function __invoke( $sedes_id ) 
    {
        // este try lo pongo porque aparentemente en esta parte es donde en donweb
        // se producía un ERROR HTTP 500. 
        try{        
                $Sede = getPrimero( \Modules\General\PublicConector::getSede(['id_sede_centro'=>$sedes_id]) );
                $motivo = MailTextoModelo::motivos('TEXTO_MODELO_ESTUDIANTE_DEUDA');

                $search = [ 'sedes_id'  => $sedes_id, 
                            'motivo'    => $motivo, 
                            'oneObject' => true 
                        ];
                // esto quizás estaria bien dentro del publicConector de General
                $MailTexto = MailTextoModelo::DBwhereGet( $search );
                
        }catch( Exeption $e){
            die( 'Error capturado en DataTextoEstudianteDeuda(): '.$e->getMessage() );
        }        
        
        return [ 'Sede'=> $Sede, 'motivo'=>$motivo, 'MailTexto'=> $MailTexto ];
    }
    
}
