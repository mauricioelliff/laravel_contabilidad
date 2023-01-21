<?php

/*
 * Brinda los subtotales de pagos en una sede para un rango de fechas
 * agrupados por las categorias declaradas en DB\RendicionSubtotales
 */

namespace Modules\Contabilidad\Models\Logic;

use Modules\Contabilidad\Models\Logic\PagosSql;
use App\Models\DBModel;
/**
 * Description of SedePagosSubtotales
 *
 * @author mauricio
 */
abstract class SedePagosTotales extends \App\Models\UseCase
{
    
    public static function get( FiltroDePagos $FiltroDePagos, bool $totalizar=false )
    {
        $sql = PagosSql::sql( $FiltroDePagos, $totalizar );
        $DBModel = new DBModel();
        return $DBModel::DBrawGet($sql);
    }
    
}
