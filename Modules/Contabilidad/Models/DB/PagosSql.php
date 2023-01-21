<?php

/*
 * Brinda el sql necesario para obtener los pagos uno a uno, 
 * con sus datos de curso asociado, estudiante, y elemento valuado.
 * referentes a las categorias en RendicionSubtotales,
 * o su sumatoria.
 */

namespace Modules\Contabilidad\Models\DB;

use Modules\Contabilidad\Models\Logic\FiltroDePagos;

/**
 * Description of Pagos
 *
 * @author mauricio
 */
abstract class PagosSql extends \App\Models\UseCase
{
    public static function sql( FiltroDePagos $FiltroDePagos, bool $totalizar=false )
    {
        return 
                'SELECT '.self::_selectCampos($totalizar)
                . 'FROM yoga_cuentas_corrientes AS ctas '
                . 'LEFT JOIN yoga_cuentascorrientes_elementosvaluados AS ev '
                .   'ON ev.cuentas_corrientes_id = ctas.id '
                . 'INNER JOIN view_elementosvaluados_por_sedes_cursos_y_planes AS evscxa '
                .   'ON evscxa.evscxa_id = ev.elementosvaluados_sedes_cursosxanio_id '
                . 'INNER JOIN view_alumnos_por_sedes_cursos_y_planes as cursada '
                .   'ON cursada.alumnos_id = ctas.alumnos_id '
                .   ' AND cursada.sedes_cursosxanio_id = evscxa.sedes_cursosxanio_id '
                
                . 'WHERE ctas.tipo_operacion LIKE "PAGO%" '
                .       (!empty($FiltroDePagos->whereString())? (' AND '.$FiltroDePagos->whereString()) : '')
                    //'AND ctas.fecha_hora_de_sistema BETWEEN "'.$fecha_desde.'" AND "'.$fecha_hasta.'" '
                    // 'AND ev.elementosvaluados_sedes_cursosxanio_id IS NOT NULL'. // Items desde Practicantes
                    //'AND cursada.sedes_id = '.$sedes_id
                    //(($wheres)? self::getWhereEnUnaLinea($wheres) : '' )
                . self::_groupBy($totalizar) // ojo Model ya tiene su metódo groupBy
            ;
    }
    
    private static function _selectCampos( bool $totalizar=false )  
    {
        $string =   self::_sqlIdentificaCategoriaEstudio().' AS categoria_estudio, '
                    . self::_sqlIdentificaCategoriaMonetizado().' AS categoria_monetizado, ';
        if( !$totalizar ){
            $string.=   'cursada.nombre_humano, cursada.nombre_computacional, cursada.clasificador_valor, cursada.sedes_cursosxanio_id,'
                      . 'evscxa.ev_abreviatura, '
                      . 'ctas.*, ctas.id as cuentas_corrientes_id, '
                      . 'cursada.nombres, cursada.apellido, cursada.nombre_espiritual, '
                      . 'ev.elementosvaluados_sedes_cursosxanio_id AS evscxa_id ';
        }else{
            $string.= self::_pagoAsignado($totalizar);
        }
        return $string;        
    }
    private static function _pagoAsignado(bool $totalizar=false){
        return ($totalizar)? ' SUM(ev.pago_asignado) AS total_pago_asignado ': 'ev.pago_asignado ';
    }
    private static function _groupBy(bool $totalizar=false){
        return ($totalizar)? ' GROUP BY categoria_estudio, categoria_monetizado ' : '';
    }
    
    
    
    // Condiciones para pertenecer a cada categoria
    private static function _categoriaEstudio()
    {
        return 
            [ 
                // la key es la de RendicionSubtotales
                'profesorado'   => 'cursada.nombre_computacional = "profesorado" ', 
                'reiki'         => 'cursada.nombre_computacional = "reiki" ', 
                'plataforma'    => '(cursada.nombre_computacional = "servicio" AND cursada.clasificador_valor = "1" ) '
                                    . 'OR ( ev.elementosvaluados_sedes_cursosxanio_id IS NULL AND ctas.motivo LIKE "%plataforma%" )', 
                'taller'        => 'ev.elementosvaluados_sedes_cursosxanio_id IS NULL AND ctas.motivo LIKE "%taller%" ',
                'clases'        => 'ev.elementosvaluados_sedes_cursosxanio_id IS NULL AND ctas.motivo LIKE "%clases%" ',
                ];
    }
    private static function _categoriaMonetizado()
    {
        return 
            [     
                // la key es la de RendicionSubtotales
                'matricula'     => 'evscxa.ev_abreviatura = "MAT" ', 
                'examen'        => 'evscxa.ev_abreviatura LIKE "%EX%" ', 
                'cuota'         => 'evscxa.ev_abreviatura LIKE "CU%" ',
                'mes'           => 'evscxa.ev_abreviatura LIKE "MES%" ', 
            ];
    }
        
    // Arma la cadena IF para detectar cual es su categoria de estudio
    private static function _sqlIdentificaCategoriaEstudio()
    {
        $condicionesCategoria = self::_categoriaEstudio();
        $field = '';
        foreach( $condicionesCategoria as $categoria => $condicion ){
            $field.= 'IF( '.$condicion.", '$categoria', ";
        }
        $field.= 'NULL '.str_repeat( ')', count($condicionesCategoria) );
        return $field;
    }
        
    // Arma la cadena IF para detectar cual es su categoria de monetizado
    private static function _sqlIdentificaCategoriaMonetizado()
    {
        $condicionesCategoria = self::_categoriaMonetizado();
        $field = '';
        foreach( $condicionesCategoria as $categoria => $condicion ){
            $field.= 'IF( '.$condicion.", '$categoria', ";
        }
        $field.= 'NULL '.str_repeat( ')', count($condicionesCategoria) );
        return $field;
    }
    
    
}
