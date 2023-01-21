<?php

/*
 * 
 */

namespace Modules\Contabilidad\Usecases\Mails;

use Modules\Contabilidad\Usecases\Deudas\InfoDeudaEstudiante;
use Modules\Contabilidad\Emails\MailDeudas;
use Modules\General\Models\DB\MailTextoModelo;
use Modules\General\Models\DB\MailEnviado;
use Modules\General\Models\Logic\MailComponentes;


use Illuminate\Support\Facades\Mail;

/**
 * Description of GuardarMailTextoModelo
 *
 * @author mauricio
 */
class EnviarDeudaEstudiante extends \App\Models\UseCase
{
    
    /*
     * OUTPUT devuelve toda la info que la operatoria utilizo
     */
    public function __invoke( array $estudianteIds, $sedes_id, $enviarDeudas ) 
    {
        
        $InfoDeudaEstudiante = new InfoDeudaEstudiante();
        $info = $InfoDeudaEstudiante->deEstudiante( $estudianteIds );
        /* $info
         * <array>
         *     'Sede'              => <objeto Sede> 
         *     'cursos'            => <array de objetos Curso> 
         *     'sedesCursosxanio'  => <array de objetos SedeCursosxanio>  
         *     'estudiantes'       => <array de objetos Estudiante> 
         *     'deudas'            => <array>
         *     'mails'             => <array> con data de los mails enviados
         */
        $motivo = MailTextoModelo::motivos('TEXTO_MODELO_ESTUDIANTE_DEUDA');
        
        $buscar = [ 'motivo'=>$motivo, 'sedes_id'=>$sedes_id,'oneObject'=>true ];
        $MailTextoModelo = MailTextoModelo::DBwhereGet( $buscar );

        foreach( $info['estudiantes'] as $dni => $Estudiante ){
            $deudasDelEstudiante = $info['deudas'][$dni];
            $MailComponentes = // encapsulo la data en un objeto
                    new MailComponentes([
                        'from'          => $info['Sede']->email_sede,
                        'mail'          => $Estudiante->mail,
                        'patrones'      => [ 
                                '%NOMBRE%'  => $Estudiante->nombres, 
                                '. '        => '.<br>',
                                "\n"        => '<br>' 
                            ],
                        'asunto'        => $MailTextoModelo->asunto,
                        'saludo_inicial'=> $MailTextoModelo->saludo_inicial,
                        'texto'         => $MailTextoModelo->texto,
                        'saludo_final'  => $MailTextoModelo->saludo_final,
                        'deudas'        => (($enviarDeudas)? $deudasDelEstudiante : null),
                        'motivo'        => $motivo,
                        'dni'           => $dni
                        ]);
            
            Mail::to( $Estudiante->mail )->send( new MailDeudas($MailComponentes) );
            
            // Registro del envío
            MailEnviado::enviado( $MailComponentes ); 
        }
        
        // Como dato informativo, devuelve todos los datos que se utilizaron.
        // Esto puede ser útil para una operatoria posterior
        return ( $info +[   'motivo' => $motivo, 
                            'MailComponentes' => $MailComponentes, // del último mail
                            'sedes_id'=>$sedes_id,
                            //'render' => $MailComponentes->render()
                        ]
                );
    }
    
}
