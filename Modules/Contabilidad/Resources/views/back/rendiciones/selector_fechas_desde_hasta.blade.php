<?php
/*
 * INPUT
 * $minFechaDesde     default null
 * $maxFechaDesde     default ayer-1
 * $minFechaHasta     default null
 * $maxFechaHasta     default ayer
 */
$ayer = restaDias(date('Y-m-d'),1);
$minFechaDesde = ( isset($minFechaDesde) )? $minFechaDesde : null;
$maxFechaDesde = ( isset($maxFechaDesde) )? $maxFechaDesde : restaDias(date('Y-m-d'),2);
$minFechaHasta = ( isset($minFechaHasta) )? $minFechaHasta : null;
$maxFechaHasta = ( isset($maxFechaHasta) )? $maxFechaHasta : $ayer;
?>
<div class="tableCss">
    <div class="tableRow">
        <div class="tableCell">Desde las 00 hs de</div>
        <div class="tableCell">
            <input  id="fecha_desde" name="fecha_desde" onchange="onCambioFechas()" 
                    type="date" class="fechasRendicion"  
                    value="<?= $fechaDesde ?>"
                    min="<?= $minFechaDesde ?>"
                    max="<?= $maxFechaDesde ?>" />
        </div>
        @error('fecha_desde')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror()
    </div>
    <div class="tableRow" style="line-height:30px">
        <div class="tableCell">Hasta las 23:59 hs de</div>
        <div class="tableCell">
            <input  id="fecha_hasta" name="fecha_hasta" onchange="onCambioFechas()" 
                    type="date" class="fechasRendicion"  
                    value="<?= $fechaDesde ?>"
                    min="<?= $minFechaHasta ?>"
                    max="<?= $maxFechaHasta ?>" />
        </div>
    </div>
</div>

