<?php

namespace Modules\Contabilidad\Models\DB;
/*
 * 
 */

/**
 * Description of Pago
 *
 * @author mauricio
 */
class Pago 
{
    // identificación de lo que paga
    public $categoria_estudio;
    public $categoria_monetizado;
    public $ev_abreviatura;
    public $evscxa_id;
    // datos del curso
    public $nombre_humano;
    public $nombre_computacional;
    public $clasificador_valor;
    // datos de la cuenta corriente
    public $cuentas_corrientes_id;
    public $origen;
    public $alumnos_id;
    public $tipo_operacion;       // FM:factura manual, FA:factura automatica, CM:cobro manual, CA:cobro automatico, NC:Nota credito 
    public $fecha_operacion;      // es la fecha a que corresponde el movimiento de dinero
    public $monto;
    public $cobertura;            // Indicador de cuanto se ha saldado este item. Utiliza un valor de igual signo que el monto.
    public $motivo;               // EV (abreviatura) directamente relacionado, o CURSO, RETIRO, A CUENTA, etc
    public $comprobante_sede;
    public $comprobante;
    public $persona_en_caja;      // quien cobro o manipulo dinero. String.
    public $observaciones;
    public $usuario_nombre;       // Es el operador logueado, en principio esta dentro de las variables de session pasadas por el sistema Admin
    public $fecha_hora_de_sistema;// momento en que se crea la row. Fines de auditoria
    // estudiante:
    public $nombres;
    public $apellido;
    public $nombre_espiritual;

    public function __construct( array $attributes ) 
    {
        foreach( $attributes as $key => $value ){
            if( property_exists($this, $key) ){
                $this->$key = $value;
            }
        }
    }
    
}
