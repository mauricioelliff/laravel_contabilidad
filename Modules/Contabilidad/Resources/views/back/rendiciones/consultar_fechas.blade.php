<?php

$hoy = date( 'Y-m-d' ); 
$ayer = restaDias($hoy,1);
$priMes = date('Y-m-').'01';
$fechaDesde = $fecha_desde;
$fechaHasta = $fecha_hasta;
?>
<style>
    .tableCss{
        display:table;
        margin-bottom:20px;
        font-size:14px !important;
        line-height:22px;
    }
    .tableRow{
        display:table-row;
    }
    .tableCell{
        display:table-cell;
        padding-left:10px;
        vertical-align: middle;
    }
    
    .espaciosSimples{
        padding-left:10px;
        text-align: left;
    }
    .readonly{
        max-width:98px;
        border-radius:5px;
        max-height:16px;
        color:#999;
        padding:3px 10px 3px 10px;
    }
    .fechasRendicion{
        width:120px;
        border-radius:5px;
    }
    .bordeBox{
        border-radius:5px;
        border:#ccc solid 1px;
        padding:15px;
        font-size:12px;
        line-height:22px;
    }
    .circulo{
        display:block;
        height: 10px;
        width: 10px;
        border-radius: 50%;
        border: 1px solid #999;
        background:#ccc;
      }
    .triangulo{
        width: 0; 
        height: 0; 
        border-left: 16px solid #999;
        border-top: 8px solid transparent;
        border-bottom: 8px solid transparent; 
        margin-left:15px;
    }
    .trianguloAbajo{
        transform: rotate(90deg);
        border-left: 16px solid dodgerblue;
    }
    .link{
        cursor:pointer;
    }
    #rendicionTotalMAT, 
    #rendicionTotalCU, 
    #rendicionTotalEX, 
    #rendicionTotalREIMAT, 
    #rendicionTotalREICU, 
    #rendicionTotalPLA{
        min-width: 60px;
        font-weight: bold;
        padding-right: 20px;
        vertical-align: middle;
    }
</style>

<form id="rendicionForm" name="rendicionForm" method="post" enctype="multipart/form-data" action="#">
    <div>
        <div class="bordeBox" style="display:inline-block;float:left;margin:0 0 20px 0" >
            <div class="tableCss">
                <div class="tableRow">
                    <div class="tableCell"><h3>Pagos</h3></div>
                    <div class="tableCell" id="pagos_mensajes" style="margin-left:-10px"></div>
                </div>
            </div>
            
            @include('contabilidad::back.rendiciones.selector_fechas_desde_hasta')
            
            @include('contabilidad::back.rendiciones.categorias')
            
            <div id="tablaTotales" class="tableCss" style="line-height:10px;">
                <div class="tableRow">
                    <div class="tableCell"><h3>Matrículas</h3></div>
                    <div class="tableCell" id="rendicionTotalMAT" style="text-align:right">0
                    </div>
                    <div class="tableCell" style="text-align:right">
                        <div class="triangulo link" title="Ver detalle"
                             onclick="onClickTriangulo(this);setGrupoEV('MAT');visualizarPagos();"> </div>
                    </div>
                </div>
                <div class="tableRow">
                    <div class="tableCell"><h3>Cuotas</h3></div>
                    <div class="tableCell" id="rendicionTotalCU" style="text-align:right">0
                    </div>
                    <div class="tableCell" style="text-align:right">
                        <div class="triangulo link"  title="Ver detalle"
                             onclick="onClickTriangulo(this);setGrupoEV('CU');visualizarPagos();"> </div>
                    </div>
                </div>
                <div class="tableRow">
                    <div class="tableCell"><h3>Examenes</h3></div>
                    <div class="tableCell" id="rendicionTotalEX" style="text-align:right">0
                    </div>
                    <div class="tableCell" style="text-align:right">
                        <div class="triangulo link"  title="Ver detalle"
                             onclick="onClickTriangulo(this);setGrupoEV('EX');visualizarPagos();"> </div>
                    </div>
                </div>
                <div class="tableRow">
                    <div class="tableCell"><h3>Plataforma Receso</h3></div>
                    <div class="tableCell"  id="rendicionTotalPLA" style="text-align:right">0
                    </div>
                    <div class="tableCell" style="text-align:right">
                        <div class="triangulo link"  title="Ver detalle"
                             onclick="onClickTriangulo(this);setGrupoEV('PLA');visualizarPagos();"> </div>
                    </div>
                </div>
                <div class="tableRow">
                    <div class="tableCell"><h3>Reiki Matrículas</h3></div>
                    <div class="tableCell" id="rendicionTotalREIMAT" style="text-align:right">0
                    </div>
                    <div class="tableCell" style="text-align:right">
                        <div class="triangulo link"  title="Ver detalle"
                             onclick="onClickTriangulo(this);setGrupoEV('REIMAT');visualizarPagos();"> </div>
                    </div>
                </div>
                <div class="tableRow">
                    <div class="tableCell"><h3>Reiki Cuotas</h3></div>
                    <div class="tableCell" id="rendicionTotalREICU" style="text-align:right">0
                    </div>
                    <div class="tableCell" style="text-align:right">
                        <div class="triangulo link"  title="Ver detalle"
                             onclick="onClickTriangulo(this);setGrupoEV('REICU');visualizarPagos();"> </div>
                    </div>
                </div>
                
                <input id='grupoEV' type="text" style="display:none" value="" />
            </div>
        </div>
        <div style="clear:both"></div>
    </div>
</form>
<?php
/* ZEND ZEND ZEND ZEND ZEND ZEND ZEND ZEND ZEND ZEND ZEND ZEND ZEND ZEND 
// Pagos en la primera muestra:
$this->params['planilla_titulo'] = 'Pagos involucrados';
$this->params['url'] =  $this->BaseUrl().
                    '/admin/datagridrows/getdatagridvalues'.  //'/grupo/'.$this->params['grupo'].
                    '/planilla/'.$this->params['planilla'].'/sede/'.$this->params['sede'].
                    '/fecha_desde/'.$fechaDesde.'/fecha_hasta/'.$fechaHasta;
// anulo funciones que no son necesarias aquí.
$this->params['datagrid_options_particulares'] = 'onLoadSuccess:null,onClickCell:null,onBeginEdit:null,onEndEdit:null';
echo $this->render('administrador/datagrid_pagos_de_toda_la_sede.phtml'); 
 ZEND ZEND ZEND ZEND ZEND ZEND ZEND ZEND ZEND ZEND ZEND ZEND ZEND ZEND */
?>


<script>

function initSedeRendicion()
{
    $('#bloqueDerecha').hide();
    $('#selectoresIconosDerecha').hide();
    setTimeout(actualizarImportes, 200);//es importante ese tiempo para que el datagrid cargue sin errores
}

function setGrupoEV( grupo )
{
    $('#grupoEV').val(grupo);
}

function onCambioFechas()
{
    if( !sonFechasValidas(true) ){
        return false;
    }    
    visualizarPagos();
    actualizarImportes();
}

function visualizarPagos()
{  
    if( !sonFechasValidas(true) ){
        return false;
    }    
    var urlLoad = getUrlPagos();
    //alert("datagrid url="+urlLoad );
    $("#tableDatagrid").datagrid({'url': urlLoad });
                                            // , 'onLoadSuccess': actualizarImportes
}
    
function actualizarImportes()
{
    var filtrosValues = getUrlParams();
    var datastring = $("#rendicionForm").serialize();
    var php = "/admin/administrador/rendicion-importes"+filtrosValues+"?"+datastring;
    //alert("actualizarImportes: "+php );
    //miLoad( 'pagos_mensajes', php, 2 );
    phpYLuegoJavascript( php, null,  'mostrarTotales(data)', null, 'pagos_mensajes', 2, false );
}
function mostrarTotales( jsonTotales )
{
    for ( var propiedad in jsonTotales ){
        $('#rendicionTotal'+propiedad).html( jsonTotales[propiedad] );
    }
}

function getUrlPagos()
{
    var url = '<?php //echo $this->BaseUrl().
                    //'/admin/datagridrows/getdatagridvalues'.  //'/grupo/'.$this->params['grupo'].
                    //'/planilla/'.$info['planilla'].'/sede/'.$info['sede']; ?>';
    var url ='/fecha_desde/'+fechaDesde()+"/fecha_hasta/"+fechaHasta()+'/grupoEV/'+$('#grupoEV').val();
    return url;
}


function fechaDesde()
{
    return $('#fecha_desde').val();
}

function fechaHasta()
{   
    return $('#fecha_hasta').val();
}
/*
 * INPUT
 * fecha1, fecha2   <string> yyyy-mm-dd
 */
function diasEntre( fecha1, fecha2 )
{
    var fecha1Date = new Date(fecha1).getTime();
    var fecha2Date = new Date(fecha2).getTime();
    return  ( ( fecha1Date - fecha2Date ) / ( 1000*60*60*24 ) );
}

function sonFechasValidas( colocarAlert )
{
    var r = ( fechaDesde() <= fechaHasta() );
    if( !r && colocarAlert==true ){
        miAlert("La fecha 'desde' es mayor que la fecha 'hasta'.");
    }
    return r;
}

function onClickTriangulo( elementoDom )
{
    quitarTrianguloAbajo();
    ponerTrianguloAbajo( elementoDom );
}

function ponerTrianguloAbajo( elementoDom )
{
    $(elementoDom).attr('class', 'triangulo trianguloAbajo link');
}
function quitarTrianguloAbajo()
{
    $('.triangulo').removeClass('trianguloAbajo');
}

// recargarPantallaRendicion() se dispara desde la respuesta del controller.
function recargarPantallaRendicion()
{
    cambiarPlanilla();
}

initSedeRendicion();

</script>
