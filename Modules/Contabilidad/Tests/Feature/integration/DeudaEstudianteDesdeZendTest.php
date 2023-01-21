<?php

namespace Modules\Contabilidad\Tests\Feature\integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeudaEstudianteDesdeZendTest extends TestCase
{
    public function setUp(): void {
        parent::setUp();
        require_once public_path('cargar_seteo.php');
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_deudas_desde_zend()
    {
        //$response = $this->get('/');
        //$response->assertStatus(200);
        
        $d = new \Modules\Contabilidad\Usecases\Deudas\DeudaEstudiante();
        $deudas = $d->deEstudiantesDeLaSede( SEDE_CENTRAL_ID, date('Y') );
        $deudasDelPrimerEstudiante = getPrimero( $deudas );
        $primeraDeuda = getPrimero($deudasDelPrimerEstudiante);
        
        $this->assertIsArray( $deudas );
        $this->assertIsArray( $deudasDelPrimerEstudiante );
        $this->assertArrayHasKey( 'dni', $primeraDeuda );
        $this->assertArrayHasKey( 'monto_deuda', $primeraDeuda );
        $this->assertArrayHasKey( 'sedes_cursosxanio_id', $primeraDeuda );
        //$this->assertArrayHasKey( 'evscxa_id', $primeraDeuda ); // podría no estar si viene desde Practicantes
    }
}
