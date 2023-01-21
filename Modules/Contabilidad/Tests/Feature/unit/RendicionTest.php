<?php

namespace Modules\Contabilidad\Tests\Featura\unit;

use Modules\Contabilidad\Models\DB\Rendicion;
use Modules\Contabilidad\Models\DB\RendicionTotales;

use Carbon\Carbon;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RendicionTest extends TestCase
{
    protected $ultimaRendicion;
    protected $totales;
    
    public function setUp(): void {
        parent::setUp();
        require_once public_path('cargar_seteo.php');
        //
        $this->ultimaRendicion = Rendicion::DBlastGet( 'fecha_hasta');
        $this->totales = $this->ultimaRendicion->hasMany( RendicionTotales::class )->get();
    }
    
    /**
     *
     * @return void
     */
    
    
    public function testArrayDeRendicionesYSusTotales()
    {
        $fechaDesde = '';
        $fechaHasta = '';
        $rendiciones = Rendicion::
        $this->assertTrue(false);
    }
    /*
    public function testQueSePuedaCrear()
    {
        $this->assertTrue(false);
    }
    public function testQueSePuedaAnular()
    {
        $this->assertTrue(false);
    }
    public function testNoPermitaCrearNoUnique()
    {
        $this->assertTrue(false);
    }
    public function testNoPermitaCrearConFechasSolapada()
    {
        $this->assertTrue(false);
    }
    public function testNoPermitaCrearConFechaInvalida()
    {
        $this->assertTrue(false);
    }
    public function testAccederALaListaHistoricaDeUnaSede()
    {
        $this->assertTrue(false);
    }
    public function testUnaSedeNoPuedeAnularUnaRevisadaOPagada()
    {
        $this->assertTrue(false);
    }
    public function testUnaBorradaNoExisteEnLasHistoricas()
    {
        $this->assertTrue(false);
    }
     * 
     */
    
    
}
