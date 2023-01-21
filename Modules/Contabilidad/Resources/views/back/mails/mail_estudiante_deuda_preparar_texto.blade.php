<?php

// Default values:
$DEFAULTS = [
    'asunto'    =>  'Mensaje de Escuela de Natha Yoga',
    'saludo_inicial'=>'Namaste %NOMBRE%',
    'texto'     =>  'Este es un mensaje automático '.
                    'para recordarles que ya puede abonarse la cuota de este mes. '.
                    'Debajo verás el detalle de lo que nos figura a pagar. '.
                    'Si ves algo incorrecto o tenes cualquier duda '.
                    'comunícate con nosotros o conmigo.  ',
    'saludo_final'=>'Que tengas hermoso día! '
];

if( isset($data['MailTexto']) ){
    $asunto     = $data['MailTexto']->asunto;
    $saludo     = $data['MailTexto']->saludo_inicial;
    $texto      = $data['MailTexto']->texto;
    $despedida  = $data['MailTexto']->saludo_final;
}else{
    $asunto     = $DEFAULTS['asunto'];
    $saludo     = $DEFAULTS['saludo_inicial'];
    $texto      = $DEFAULTS['texto'];
    $despedida  = $DEFAULTS['saludo_final'];
}

$motivo = $data['motivo'];
$sedes_id = $data['Sede']->id_sede_centro;

?>
<style>
    .aclaracion{
        color:#999;
    }
</style>


<div align="center">
    <div class="bloque-con-elementos" style="display:inline-block;padding:2em;text-align:left;" > 

        <h2>{{ $data['Sede']->nombre_sede_centro }}</h2>

        <h3>Enviar mails a estudiantes</h3>

        <div class='aclaracion'>(Se puede usar <strong>%NOMBRE%</strong> para indicar donde colocar el nombre del estudiante)</div>

        <form id="mail_textos" name="mail_textos" method="post" action="#">
            @csrf
            
            <input type="hidden" id="motivo" name="motivo" value="{{ $motivo }}">
            <input type="hidden" id="sedes_id" name="sedes_id" value="{{ $sedes_id }}">
            <br>
            
            <h4>Asunto</h4>
            <input type="text" style="width:300px" id="asunto" name="asunto" value="{{ $asunto }}">
            </br>

            <h4>Saludo</h4>
            <input type="text" style="width:300px" id="saludo_inicial" name="saludo_inicial" value="{{ $saludo }}">
            </br>

            <h4>Texto</h4>
            <textarea rows="8" cols="40" style="width:320px" id="texto" name="texto">{{ $texto }}</textarea>
            </br>

            <div style="padding:1em;width:300px;" align="right">
                <div style="float:right">
                    <input type="checkbox" onclick="toogleTablaDeudas()"
                           id="enviarDeudas" name="enviarDeudas" />
                </div>
                <div style='color:#666;text-align: right;font-weight:bold;float:right'>Incluir Deudas</div>
            </div>
            <div style="clear:both"></div>
            <table id="tablaDeudas" style='border:1px solid #ccc;color:#999;min-width:320px' cellpadding='2'>
                <tr><td colspan='2'><strong>Estado de cuenta:</strong></td></tr>
                <tr><td colspan='2'> </td></tr>
                <tr>
                    <td>Nivel 1, Cuota 1, 2021</td><td>$100</td>
                <tr>
                <tr>
                    <td>Nivel 1, Cuota 2, 2021</td><td>$100</td>
                <tr>
                <tr><td colspan='2'> </td></tr>
            </table>
            </br>
            
            <h4>Despedida</h4>
            <textarea rows="2" cols="40" style="width:320px" id="saludo_final" name="saludo_final" >{{ $despedida }}</textarea>

            <div style="margin:2em" align="center">
                <input type="button" onclick="guardarForm('mail_textos')" value="Continuar" />
            </div>
        </form>
    </div>
</div>

<script>
    function guardarForm( formId )
    {
        var variablesPost = getFormValues( formId ); // tipo vars en url
        // var _token = document.getElementsByName('_token')[0].value;

        // Convierto el objeto/array en JSON 
        // Armo el objeto final con todos los datos
        // Es MUY IMPORTANTE, colocar el _token tal cual, para que Laravel lo tome bien.
        //var jsonFinal = { POST_checkeados : checkeadosJson, _token : _token };  

        var ruta = '{{ route( $data['ruta_guardar'] ) }}';
        var divOut = 'divDatagrid';
        var jsOK = null;
        var jsERROR = 'alert("Error al cargar datos");';
        //alert( "vamos a la ruta "+ ruta  );
        ajaxPost( ruta, variablesPost, jsOK, jsERROR, divOut );
        //phpYLuegoJavascript( prgPhpInput, variablesPost,  jsOK, jsERROR, divWork, gifNumber, colocarOverlay, BaseUrl )
    }
    
    function toogleTablaDeudas()
    {
        
    }
    
</script>

<?php // funciones js que necesita:  ?>
@include('miLibreria::js.general')
@include('miLibreria::js.formularios')
@include('miLibreria::js.contenidoDinamico')
@include('miLibreria::js.tipoDeDato')
