<?php

/*
 * 
 */

namespace Modules\Contabilidad\Usecases\Rendiciones;


use Modules\Contabilidad\Models\DB\RendicionTotales;
use Modules\Contabilidad\Usecases\Rendiciones\SedeUltimaRendicion;
use Modules\Contabilidad\Models\DB\Rendicion;

/**
 * Brinda información para poder presentar la pantalla de 
 * ingreso de fechas y consulta de subtotales.
 * Si tiene una última rendición, devuelve su info.
 *
 * @author mauricio
 */
abstract class ConsultaDeFechas 
{
    /*
     * INPUT 
     * sedes_id
     * fecha_desde  Opcional
     * fecha_hasta  Opcional
     * 
     * OUTPUT
     * <array>
     *      'sedes_id'
     *      'fecha_desde'
     *      'fecha_hasta'
     *      'domCategoriasMonetizados'  
     *      'nomCategoriasEstudio'  
     *      'nomCategoriaMonetizado'
     *  Si hay rendición agrega:
     *      'ultima'             
     *      'subtotales'         
     */
    public static function data( array $params )
    {
        $out = ['fecha_desde'   => (isset($params['fecha_desde'])? $params['fecha_desde'] : null),
                'fecha_hasta'   => (isset($params['fecha_hasta'])? $params['fecha_hasta'] : null),
                ];
        // Determina fechas default. 
        // Si hay rendición, 1 día posterior, sino mes actual:
        if( !isset($params['fecha_desde']) ){
            $UseCaseRendiciones = new SedeUltimaRendicion( $params['sedes_id'] );
            $hoy = date( 'Y-m-d' ); 
            $ayer = restaDias($hoy,1);
            $priMes = date('Y-m-').'01';
            $out = $UseCaseRendiciones->dataTotales();
                // 'ultima'             => <object>
                // 'totales'         =>  <array> <object>
            $out['fecha_desde'] = ( is_null($out['ultima']) )? $priMes : sumaDias($out['ultima']->fecha_desde,1);
            $out['fecha_hasta'] = $ayer;
        }
        $out['domCategoriasMonetizados']  = Rendicion::dominioCategoriasEstudioYSusMonetizados();
        $out['nomCategoriasEstudio']      = Rendicion::nombresCategoriaEstudio();
        $out['nomCategoriaMonetizado']    = Rendicion::nombresCategoriaMonetizado();
        $out['sedes_id'] = $params['sedes_id'];
dd('$out TODO MAU 3',$out);
        return $out;
    }
    
    
}
