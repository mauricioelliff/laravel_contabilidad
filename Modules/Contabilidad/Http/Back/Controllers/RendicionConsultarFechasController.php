<?php

namespace Modules\Contabilidad\Http\Back\Controllers;

use Modules\Contabilidad\Usecases\Rendiciones\ConsultaDeFechas;

use Modules\Contabilidad\Usecases\Rendiciones\SedeUltimaRendicion;
use Modules\Contabilidad\Models\DB\PagosData;


use App\Http\Controllers\EsteProyectoController;

//use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
//use Illuminate\Routing\Controller;

class RendicionConsultarFechasController extends EsteProyectoController
{
    public $arrayOk;
    
    public function __construct( Request $request ) 
    {
        parent::__construct( $request );
        if( isset($request->POST_json) ){
            $this->arrayOk = $this->limpiarDatos( json_decode( $request->POST_json, true ) );
            $this->arrayOk['sedes_id'] = ( isset($this->arrayOk['sedes_id']) )? $this->arrayOk['sedes_id'] : $this->arrayOk['sede'];
        }
    }
    
    public function index( Request $request )
    {
        /*
        if( !$this->arrayOk || !isset($this->arrayOk['sedes_id']) ){
            return 'Hay algún problema en el requerimiento';
            // return $this->json( $respuestas ); // EN REALIDAD AQUI ESPERA UN HTML
        }
         */      
        
        $request->merge(['fecha_desde' => '2025-13-13']);
        
        $validatedData = $request->validate([
                                        'fecha_desde' => 'required|date|date_format:Y-m-d',
                                        'fecha_hasta' => 'required|date|date_format:Y-m-d|after_or_equal:fecha_desde',
                                        ], $this->messages() );
        // todo ok
        return view('contabilidad::back.rendiciones.consultar_fechas')
                ->with( ConsultaDeFechas::data($this->arrayOk) );
    }
    
    public function messages()
    {
        return [
            'fecha_desde.required' => 'Es necesario indicar la fecha desde',
            'fecha_desde.date' => ':attribute debe ser una fecha válida',
            'fecha_desde.date_format:Y-m-d' => ':attribute No tiene el formato correcto',
            //
            'fecha_hasta.required' => 'Es necesario indicar la fecha hasta',
            'fecha_hasta.date' => 'No tiene el formato correcto',
            'fecha_hasta.after_or_equal' => 'fecha desde debe ser anterior a hasta',
        ];
    }    
    
    /* 
     * POST
     * 
     * INPUT
     * POST_checkeados  <json array> dnis seleccionados
     * sedes_id         <int>
     * 
     */
    public function ultimaRendicion( Request $request )
    {
        if( !$this->arrayOk || !isset($this->arrayOk['sedes_id']) ){
            return 'Hay algún problema en el requerimiento';
            // return $this->json( $respuestas ); // NE REALIDAD AQUI ESPERA UN HTML
        }

        $UseCaseRendiciones = new SedeUltimaRendicion( $this->sedes_id );
        $data = $UseCaseRendiciones->dataTotales();
                    // 'ultima' => <object>
                    // 'subtotales' =>  <array> <object>
                    // 'categoriasEstudio' =>  <array>
                    // 'categoriaMonetizado' => <array>
        
        //$Pagos = new PagosSql();

$arrayOk['fecha_desde'] = ( is_null($data['ultima']) )? null : $data['ultima']->fecha_desde;
$arrayOk['fecha_hasta'] = ( is_null($data['ultima']) )? null : $data['ultima']->fecha_hasta;
        $FiltroDePagos = new \Modules\Contabilidad\Models\Logic\FiltroDePagos( $arrayOk );
dump('$FiltroDePagos',$FiltroDePagos, 'stringwere', $FiltroDePagos->whereString() );        
dump('sin totalizar', PagosSql::sql( $FiltroDePagos, $totalizar=false ) );
dd('con totalizar', PagosSql::sql( $FiltroDePagos, $totalizar=true ) );
        return view('contabilidad::back.rendiciones.consultar_fechas')->with(['info'=>$data]);
    }
    
    /*
     * INPUT 
     * sede, fecha_desde, fecha_hasta
     */
    public function pagosJson( Request $request )
    {
        if( !$this->arrayOk || !isset($this->arrayOk['sedes_id']) ){
            return 'Hay algún problema en el requerimiento';
            // return $this->json( $respuestas ); // EN REALIDAD AQUI ESPERA UN HTML
        }
        
        $pagos = PagosData::getDesdeArray( $this->arrayOk );
        return $this->json($pagos);
    }
    
}
