<?php 

?>
@include('contabilidad::back.rendiciones.mostrar_subtotales')
    <div class="tableRow">
        <div class="tableCell"><h3>Matrículas</h3></div>
        <div class="tableCell" id="rendicionTotalMAT" style="text-align:right">0
        </div>
        <div class="tableCell" style="text-align:right">
            <div class="triangulo link" title="Ver detalle"
                 onclick="onClickTriangulo(this);setGrupoEV('MAT');visualizarPagos();"> </div>
        </div>
    </div>

