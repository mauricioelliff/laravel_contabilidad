<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Modules\Contabilidad\Usecases\Mails;

use Modules\General\Models\DB\MailTextoModelo;
/**
 * Description of GuardarMailTextoModelo
 *
 * @author mauricio
 */
class GuardarMailTextoModelo extends \App\Models\UseCase
{
    public function __invoke( $values ) 
    {
        $values['usuario_nombre'] = USUARIO_NOMBRE ;
        
        if( key_exists('id',$values) && !is_null($values['id']) ){
            $find = ['id' => $values['id'] ];
        }else{
            $find = [   'sedes_id'  => $values['sedes_id'],
                        'motivo'    => $values['motivo']
                    ];
        }
        $sets = arrays_getAlgunasKeys( $values, ['motivo','asunto','saludo_inicial','texto','saludo_final','usuario_nombre']);
        MailTextoModelo::DBupdateOrCreate( $find, $sets );
    }
}
