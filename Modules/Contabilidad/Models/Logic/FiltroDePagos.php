<?php

/*
 *  
 */

namespace Modules\Contabilidad\Models\Logic;

use Modules\Contabilidad\Models\DB\RendicionTotales;
/**
 * Description of PagoFiltro
 *
 * @author mauricio
 */
class FiltroDePagos 
{
    public $fecha_desde             = null;
    public $fecha_hasta             = null;
    public $sedes_id                = null;
    public $categoria_estudio       = null; 
    public $categoria_monetizado    = null;
    
    public $whereResultante         = '';
    
    public function __construct( array $attributes ) 
    {
        foreach( $attributes as $key => $value ){
            if( property_exists( get_class($this), $key ) ){
                $this->$key = $value;
            }
        }
        $this->_validates();
    }
    private function _validates()
    {
        if( $this->fecha_desde && !validateDate($this->fecha_desde) ){
            $this->fecha_desde = null;
        }
        if( $this->fecha_hasta && !validateDate($this->fecha_hasta) ){
            $this->fecha_hasta = null;
        }
        if( $this->fecha_desde > $this->fecha_hasta ){
            $this->fecha_desde = null;
            $this->fecha_hasta = null;
        }
        if( !($this->fecha_desde && $this->fecha_hasta) ){  // alguna null?
            $this->fecha_desde = null;
            $this->fecha_hasta = null;
        }
        if( $this->categoria_estudio && 
            !in_array($this->categoria_estudio, array_keys(RendicionTotales::dominioCategoriaEstudio)) ){
            $this->categoria_estudio = null;
        }
        if( $this->categoria_monetizado && 
            !in_array($this->categoria_monetizado, array_keys(RendicionTotales::dominioCategoriaMonetizado)) ){
            $this->categoria_monetizado = null;
        }
    }
    
    public function whereString()
    {
        return implode(' AND ', $this->whereArray() );
    }
    
    public function whereArray()
    {
        $array = [];
        if( !is_null($this->fecha_desde) && !is_null($this->fecha_hasta) ){
            $array[]= ' ctas.fecha_hora_de_sistema BETWEEN "'.$this->fecha_desde.'" AND "'.$this->fecha_hasta.'"';
        }
        
        if( !is_null($this->sedes_id) ){
            $array[]= ' cursada.sedes_id = '.(int)$this->sedes_id;
        }
        
        if( !is_null($this->categoria_estudio) ){
            $array[]= ' categoria_estudio = '.$this->categoria_estudio;
        }
        
        if( !is_null($this->categoria_monetizado) ){
            $array[]= ' categoria_estudio = '.$this->categoria_monetizado;
        }
        return $array;
    }
    
}
