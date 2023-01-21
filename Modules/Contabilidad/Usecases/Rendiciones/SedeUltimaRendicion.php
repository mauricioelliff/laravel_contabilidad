<?php

/*
 * Brinda data de la última Rendición de la sede,
 * sus objetos Subtotales, y 
 * el el listado del dominio de categorias de estudio y categorias de monetizado
 */

namespace Modules\Contabilidad\Usecases\Rendiciones;

use Modules\Contabilidad\Models\DB\Rendicion;
use Modules\Contabilidad\Models\DB\RendicionTotales;


/**
 * Description of SedeCreaRendicion
 *
 * @author mauricio
 */
class SedeUltimaRendicion extends \App\Models\UseCase
{
    public  $sedes_id;
    public  $ultimaRendicion;
    
    public function __construct( $sedes_id )
    {
        parent::__construct();
        $this->sedes_id = $sedes_id;
        $this->ultimaRendicion = Rendicion::ultima( $sedes_id );
    }
    
    public function ultima(){
        return $this->ultimaRendicion;
    }
    
    public function totales(){
        return (is_null($this->ultimaRendicion))? null : Rendicion::totales( $this->ultimaRendicion ); 
    }
        
    public function dataTotales()
    {
        return ['ultima' => $this->ultimaRendicion, 
                'totales' => $this->totales(),
                'categoriasEstudio'     => Rendicion::nombresCategoriaEstudio(), 
                'categoriaMonetizado'   => Rendicion::nombresCategoriaMonetizado(), 
                ];
    }
    
}
