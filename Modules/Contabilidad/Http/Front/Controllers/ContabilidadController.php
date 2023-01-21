<?php

namespace Modules\Contabilidad\Http\Front\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ContabilidadController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        
        dd('estoy en index de frontend contabilidad');
        // API ZEND:
        include( __GESTION_DIR_ABSOLUTO_AL_PROYECTO__.'api/cuentas_corrientes/pagos.php');
        
        $sedes_id = 3;
        $deudas = obtenerMovimientosQuePuedenSerPagadosSede( $sedes_id );
        dd( $deudas );
        
        dd( \Modules\Contabilidad\Models\DB\CuentaCorriente::find(99950)->toArray() );
        return 'ESTAS EN CONTABILIDAD:INDEX';
        return view('contabilidad::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('contabilidad::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('contabilidad::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('contabilidad::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
