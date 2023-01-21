<?php
    /* INPUT
     * $info
     * <array>
     *      'Sede'      => <object Sede>
     *      'cursos'    => <array Curso>
     *      'estudiantes' => <array Alumno>
     *      'sedesCursosxanio'  => <array SedeCursoxanio>
     *      'mails      => <array> con data de los mails enviados a cada estudiante
     *                          dni =>  'dni'
     *                                  'cantidad'
     *                                  'ultimo'
     *      'enviarDeudas'  <boolean>
     *      'deudas'    => array(179) { 
     *                          [21495380]=> array(2) { 
     *                              [0]=> array(10) { 
     *                                       "dni" => "21495380"
     *                                       "id" => 6289
     *                                       "descripcion" => "Profesorado de Yoga Curso 1"
     *                                       "descripcion_corta" => "Nivel 1, Cuota 4, 2021"
     *                                       "monto_deuda" => 4200
     *                                       "scxa_id" => 590
     *                                       "cursos_id" => 3
     *                                       "nombre_humano" => "Profesorado Natha Yoga Nivel 1"
     *                                       "ev_abreviatura" => "CU4"
     *                                       "evscxa_id" => 6289
     *      'ruta_guardar' => nombre de la ruta para procesar la view
     */

// obtiene la data de los estudiantes que cumplen el clousure
$valuesConMail = loopEstudiantes( $info, function($e){ return ($e->mail)? true : false; });
$valuesSinMail = loopEstudiantes( $info, function($e){ return (!$e->mail)? true : false; });
//
if( count($valuesConMail)>0 ){
    $valuesConMail = arrays_ordenarSegunKeys( $valuesConMail, 'total', SORT_DESC );
}
if( count($valuesSinMail)>0 ){
    $valuesSinMail = arrays_ordenarSegunKeys( $valuesSinMail, 'total', SORT_DESC );
}

$enviarDeudas = ( key_exists('enviarDeudas',$info) && $info['enviarDeudas'] )? true : false;
?>

<style>
    .div_lista {
        direction: rtl;         /* RTL scrollbar left */
        display:inline-block;
        max-height:500px;
        overflow-y:scroll;
    }

    .tarjeta_deudas{
        direction:ltr;          /* LTR scrollbar left */
        font-size:1.2em;
        padding:12px;
        /* background-color: gainsboro; */
        max-width:600px;
    }
    .enviados{
        font-size:1.2em;
        display: inline-block;
        padding:5px 5px 5px 0;
        color:#666;
    }
    .ultimo{
        font-size:1.2em;
        display: inline-block;
        margin-left:3em;
        padding:5px;
        color:#666;
    }
    
    
    .deuda_check{
        float:left;
        margin-right: 1em;
        vertical-align: top;
    }
    .deuda_nombres{
        float:left;
        width:100%;
        font-weight:bold;
        font-size:1.1em;
        margin-bottom:5px;
    }
    .deuda_estudiante{
        clear:both;
        color:#333;
        line-height:1.7em;
    }
    .deuda_total_estudiante{
        font-size:1.1em;
        font-weight:bold;
        color:#666;
    }
    .string_deuda{
        
    }
    .check_todos_texto{
        color:#333;
        text-decoration: italic;
        font-size:1.3em;
        padding-left: 3px;
    }
    .boton{
        margin:1em;
    }
    .centrar{
        display: flex;
        justify-content: center;
    }
</style>

<div align='center'>
<div class='bloque-con-elementos div_lista'>
    <form id='mail_cuentas_estudiantes' method="post" action='#'>
        @csrf

        <div style='display:inline-block' align='left'>
            <h2><?= $info['Sede']->nombre_sede_centro ?></h2>
            <h2>Enviar mails</h2>

            <div align="right" style="color:#333;padding-right:2em">
                Incluir sus deudas: <strong><?= ( $enviarDeudas )? 'SI' : 'NO'; ?></strong>
                <input type="hidden" id="enviarDeudas" value="<?= ( $enviarDeudas )? '1' : '0'; ?>" />
            </div>
            
            <div style='min-width:100%;padding-top:1em;padding-bottom:3em;'>
                <div class='deuda_check' style='margin-left:13px'>
                    <span class='check_todos_texto'>Todos</span>
                    <input type='checkbox' onclick='toogleCheckboxes(this);' />
                </div>
            </div>
            <div style='clear:both'></div>
            @foreach( $valuesConMail as $values )
                @include( 'contabilidad::back.mails.mail_estudiante_deuda_seleccionar_estudiantes_tarjeta_estudiante', $values )
            @endforeach
        </div>    

        <div class='centrar' style='margin-bottom:2em'>
            <input class='boton' type="button" onclick="guardarSeleccion('mail_cuentas_estudiantes');return false;" value="Enviar" />    
        </div>

    </form>    

    @if( count($valuesSinMail)>0 )
    <div align='center'>
        <div style='display:inline-block' align='left'>
            <h2 style="color:#666">Estudiantes sin mail</h2>
            {{ $conCheck=false; }}
            <div style="color:#999">
            @foreach( $valuesSinMail as $values )
                @include( 'contabilidad::back.mails.mail_estudiante_deuda_seleccionar_estudiantes_tarjeta_estudiante', $values )
            @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
</div>

<script>
    function toogleCheckboxes( referenceCheckbox ){
        $(':checkbox[name=check_estudiante]').prop('checked', referenceCheckbox.checked );
    }
    
    function guardarSeleccion( formId )
    {
        // var variablesPost = getFormValues( formId ); // tipo vars en url                    
        var checkeadosArray = getCheckedIds('check_estudiante');
        if( checkeadosArray.length == 0 ){
            miAlert('No has seleccionado estudiantes',true);
            return false;
        }

        var _token = document.getElementsByName('_token')[0].value;
        var sede = $('#sede').val();
        var enviarDeudas = $('#enviarDeudas').val();

        // Convierto el objeto/array en JSON 
        var checkeadosJson = objectToJson(checkeadosArray); // JSON.stringify( checkeadosArray );
        // Armo el objeto final con todos los datos
        // Es MUY IMPORTANTE, colocar el _token tal cual, para que Laravel lo tome bien.
        var jsonFinal = { POST_checkeados : checkeadosJson, _token : _token, sedes_id : sede, enviarDeudas : enviarDeudas };  

        var ruta = '{{ route( $info['ruta_guardar'] ) }}';
        var divOut = 'divDatagrid';
        var jsOK = 'scrollHastaUnaDiv("div_lista");';
        var jsERROR = 'alert("Error al cargar datos");';
        // alert( "vamos a la ruta "+ ruta  );
        ajaxPost( ruta, jsonFinal, jsOK, jsERROR, divOut );
    }
    

</script>
<?php // funciones js que necesita:  ?>
@include('miLibreria::js.general')
@include('miLibreria::js.formularios')
@include('miLibreria::js.contenidoDinamico')
@include('miLibreria::js.tipoDeDato')

<?php 

/* 
 * Devuelve los datos de los estudiantes que cumplen la condición.
 * OUTPUT
 * <array>
 *      'Estudiante'
 *      'deudas'
 *      'total
 */
function loopEstudiantes( $info, $condicion )
{
    $values = [];
    foreach( $info['deudas'] as $dni => $deudas ){

        $Estudiante = $info['estudiantes'][$dni];
        if( !$condicion($Estudiante) ){
            continue;
        }
        $r = getLineasDeDeuda( $deudas, $info['sedesCursosxanio'] ); 
        $values[] = ['Estudiante'=>$Estudiante, 'deudas'=>$r, 'total'=>$r['total']];
    } 
    return $values;
}

/*
 * INPUT 
 *  array
 *      array
 *          [dni] => 21495380
 *          [id] => 6289
 *          [descripcion] => Nivel 1, Cuota 4, 2021
 *          [descripcion_corta] => Nivel 1, Cuota 4, 2021    ev_abreviatura
 *          [monto_deuda] => 4200
 *          'sedes_cursosxanio_id'      
 *          'cursos_id'    
 *          'nombre_curso'
 *          'descripcion_curso'  
 *          'ev_abreviatura'
 *          'evscxa_id'    
 * 
 * OUTPUT
 * <array>
 *      key $cursoNombre  <array>
 *                  'lineas' <array>    
 *                              Nivel 1 2021, Cu4, Cu5, Cu6.
 *                              Reiki 2021, Cu1.
 *                  'total'     int
 */
function getLineasDeDeuda( array $deudasEstudiante, array $sedesCursosxanio )
{
    $resultado = [];
    $cursosNombres = [];
    $total = 0;
    foreach( $deudasEstudiante as $array ){
        if( $array['descripcion'] ){
            //$cursoNombre = substr( $array['descripcion'], 0, strpos($array['descripcion'],','));
            $cursoNombre =  $sedesCursosxanio[$array['sedes_cursosxanio_id']]->anio 
                            .' '.getNombreCurso( $array['descripcion'] );
            if( !key_exists( $cursoNombre, $resultado ) ){
                //$resultado[ $cursoNombre ] = [];
                $resultado[ $cursoNombre ]= '';
            }
            $resultado[ $cursoNombre ].= ucfirst(strtolower($array['ev_abreviatura'])).', ';
        }else{
            $resultado[ 'Consultar estado de cuenta' ] = '';
        }
        $total+= $array['monto_deuda'];
    }
    // cambio la ',' final por '.'
    foreach( $resultado as $cursoNombre => $stringDeudas ){
        if( substr( $stringDeudas, -2 ) == ', ' ){
            $resultado[$cursoNombre]= substr($stringDeudas,0,-2).'.';
        }
    }
    // Ordeno segun el string de nombrecurso
    ksort($resultado);
    
    $resultado['total']= $total;
    return $resultado;
}
function getNombreCurso( $cadena )
{
    $array = explode( ',', $cadena );
    if( !key_exists(1,$array) ){
        return "NO SE ENCONTRO CURSO EN ESTA CADENA: $cadena ";
    }
    return $array[0];
}
