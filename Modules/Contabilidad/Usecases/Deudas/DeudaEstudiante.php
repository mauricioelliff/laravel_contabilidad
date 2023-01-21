<?php

/*
 * 
 */

namespace Modules\Contabilidad\Usecases\Deudas;

include( __GESTION_DIR_ABSOLUTO_AL_PROYECTO__.'api/cuentas_corrientes/pagos.php');
// da acceso a la función  obtenerMovimientosQuePuedenSerPagadosSede()

/**
 * Description of DeudasEstudiante
 *
 * @author mauricio
 */
class DeudaEstudiante extends \App\Models\UseCase
{
    /*
     * OUTPUT
     * <array> array(179) { 
     *      [21495380]=> array(2) { 
     *          [0]=> array(10) { 
     *                   "dni" => "21495380"
     *                   "id" => 6289
     *                   "descripcion" => "Profesorado de Yoga Curso 1"
     *                   "descripcion_corta" => "Nivel 1, Cuota 4, 2021"
     *                   "monto_deuda" => 4200
     *                   "sedes_cursosxanio_id" => 590
     *                   "cursos_id" => 3
     *                   "nombre_humano" => "Profesorado Natha Yoga Nivel 1"
     *                   "ev_abreviatura" => "CU4"
     *                   "evscxa_id" => 6289
     */
    public function deEstudiantesDeLaSede( $sedes_id, $anio ) : array
    {
        return obtenerMovimientosQuePuedenSerPagadosSede( $sedes_id, $anio );
    }
    public function deEstudiantes( $alumnos_ids ) : array
    {
        return obtenerMovimientosQuePuedenSerPagados( $alumnos_ids );
    }
    
    
}
