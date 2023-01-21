<?php

/*
 * Devuelve data de los pagos involucrados en una rendición o fechas indicadas
 * (devuelve en modo array de datos asociativos y no de objetos)
 */

namespace Modules\Contabilidad\Models\DB;

use Modules\Contabilidad\Usecases\Rendiciones\SedeUltimaRendicion;
use Modules\Contabilidad\Models\Logic\FiltroDePagos;
use Modules\Contabilidad\Models\DB\PagosSql;
use Modules\Contabilidad\Models\DB\Pago;

use App\Models\DBModel;

/**
 * Description of Pagos
 *
 * @author mauricio
 */
abstract class PagosData 
{
    /*
     * INPUT
     * <array>
     *      'sedes_id'
     *      'fecha_desde'   opcional
     *      'fecha_hasta'   opcional
     */
    public static function getDesdeArray( $array )
    {
        return self::get( new FiltroDePagos( $array ) );
    }
    
    
    public static function get( FiltroDePagos $FiltroDePagos )
    {
        $FiltroDePagos = self::_setFechas( $FiltroDePagos );
        $sql = PagosSql::sql( $FiltroDePagos, $totalizar=false );
        $datas = DBModel::DBselect( $sql, null, true ); // la key no puede ser ctacteid xq podría haber más de una
        $pagos = [];
        foreach( $datas as $rowArray ){
            $pagos[] = new Pago($rowArray);
        }
        return $pagos;
    }
    
    private static function _setFechas( $FiltroDePagos )
    {
        if( !isset($FiltroDePagos->fecha_desde) ){
            // calculo los valores para fecha_desde, fecha_hasta
            $UseCaseRendiciones = new SedeUltimaRendicion( $FiltroDePagos->sedes_id );
            $ultima = $UseCaseRendiciones->ultima();
            $ultimaMas1Dia = (!$ultima)? null : sumaDias($ultima->fecha_hasta,1);
            $hoy = date( 'Y-m-d' ); 
            $ayer = restaDias($hoy,1);

            $FiltroDePagos->fecha_desde = ( isset($array['fecha_desde']) && validateDate($array['fecha_desde']) )? $array['fecha_desde'] : ( ($ultima)? $ultima->fecha_hasta : date('Y-m-').'01');
            $FiltroDePagos->fecha_hasta = ( isset($array['fecha_hasta']) && validateDate($array['fecha_hasta']) && $array['fecha_hasta']<=$ayer )? $array['fecha_hasta'] : $ayer;
        }
        return $FiltroDePagos;
    }
   

}
