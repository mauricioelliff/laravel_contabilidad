<?php
/*
 * Brinda la información referente a deudas de los estudiantes en la SEDE.
 * ( Su cuenta, su nombre, la sede, la cantidad de mails enviados en el mes, ...)
 * 
 */

namespace Modules\Contabilidad\Usecases\Deudas;

use Modules\Contabilidad\Usecases\Deudas\DeudaEstudiante;
use Modules\Estudiante\Models\DB\Estudiante;
use Modules\General\Models\DB\MailEnviado;
/**
 * Description of PreEnviarDeudas
 *
 * @author mauricio
 */
class InfoDeudaEstudiante extends \App\Models\UseCase
{    
    
    /*
     * Brinda info respecto de las deudas de los estudiantes en la Sede 
     * OUTPUT
     * <array>
     *     'Sede'              => <objeto Sede>
     *     'cursos'            => <array de objetos Curso>
     *     'sedesCursosxanio'  => <array de objetos SedeCursosxanio>
     *     'estudiantes'       => <array de objetos Alumno>
     *     'deudas'            => <array>
     *                          [21495380]=> array(2) { 
     *                              [0]=> array(10) { 
     *                                       "dni" => "21495380"
     *                                       "id" => 6289
     *                                       "descripcion" => "Profesorado de Yoga Curso 1"
     *                                       "descripcion_corta" => "Nivel 1, Cuota 4, 2021"
     *                                       "monto_deuda" => 4200
     *                                       "sedes_cursosxanio_id" => 590
     *                                       "cursos_id" => 3
     *                                       "nombre_humano" => "Profesorado Natha Yoga Nivel 1"
     *                                       "ev_abreviatura" => "CU4"
     *                                       "evscxa_id" => 6289
     *      'mails'             => <array por dni>
     *                                       'dni'     
     *                                       'mes',
     *                                       'cantidad'
     *                                       'ultimo'                                  
     */
    public function enLaSede( $sedes_id, $anio=null ) 
    {
        $anio = ($anio)? $anio : date('Y');
        $Sede = getPrimero( \Modules\General\PublicConector::getSede(['id_sede_centro'=>$sedes_id]) );
        $DeudaEstudiante = new DeudaEstudiante();
        $deudas = $DeudaEstudiante->deEstudiantesDeLaSede( $sedes_id, $anio ); 
        return ['deudas'=>$deudas,'Sede'=>$Sede] + $this->_otrasDatas( $deudas );
    }
    
    public function deEstudiante( $alumnosIds )
    {
        $DeudaEstudiante = new DeudaEstudiante();
        $deudas = $DeudaEstudiante->deEstudiantes( $alumnosIds );   
        $deudas = (count($alumnosIds)==1)? [getPrimero($alumnosIds)=>$deudas] : $deudas; 
        return ['deudas'=>$deudas] + $this->_otrasDatas( $deudas );
    }
    
    
    private function _otrasDatas( $deudas )
    {
        $alumnosIds = ($deudas)? array_keys( $deudas ) : [];
        $estudiantes = ($deudas)? Estudiante::DBwhereGet([ 'dni'=>$alumnosIds ]) : null;
        $sedesCursosxanio = \Modules\Cursada\PublicConector::getSedeCursosxanio( ['id'=>$this->_getCursadasIds($deudas)] );
        $ultimoScxa = end( $sedesCursosxanio );
        $Sede = getPrimero( \Modules\General\PublicConector::getSede(['id_sede_centro'=>$ultimoScxa->sedes_id]) );
        $cursos = \Modules\Cursada\PublicConector::getCurso();
        $dataMailsEnviados = MailEnviado::infoEnviados( $alumnosIds );
        return [
                'cursos'            => $cursos,
                'sedesCursosxanio'  => $sedesCursosxanio,
                'Sede'              => $Sede,
                'estudiantes'       => $estudiantes,
                'mails'             => $dataMailsEnviados
                //'deudas'            => $deudas,
                ];
    }
    
    /*
     *      'deudas'    => array(179) { 
     *                          [21495380]=> array(2) { 
     *                              [0]=> array(10) { 
     *                                       "dni" => "21495380"
     *                                       "id" => 6289
     *                                       "descripcion" => "Profesorado de Yoga Curso 1"
     *                                       "descripcion_corta" => "Nivel 1, Cuota 4, 2021"
     *                                       "monto_deuda" => 4200
     *                                       "sedes_cursosxanio_id" => 590
     *                                       "cursos_id" => 3
     *                                       "nombre_humano" => "Profesorado Natha Yoga Nivel 1"
     *                                       "ev_abreviatura" => "CU4"
     *                                       "evscxa_id" => 6289
     */
    private function _getCursadasIds( $deudas )
    {
        $cursadas = [];
        foreach( $deudas as $key => $deudasEstudiante ){
            foreach( $deudasEstudiante as $array ){
                if( !is_array($array) || !key_exists('sedes_cursosxanio_id',$array)
                        || is_null($array['sedes_cursosxanio_id']) ){
                    dd('Error en server. Falla en recopilación de deudas',$key,$array,$deudasEstudiante );
                }
                $cursadas[]= $array['sedes_cursosxanio_id'];
            }
        }
        return array_unique($cursadas);
    }
}
