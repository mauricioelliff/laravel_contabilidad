<?php
/*
 * INPUT
 * 
 *      'sedes_id'  
 *      'fecha_desde'
 *      'fecha_hasta'
 *      'domCategoriasMonetizados'     // indica los monetizados de cada categoria  
 *      'nomCategoriasEstudio'  
 *      'nomCategoriaMonetizado'
 *  Si hay rendición agrega:
 *      'ultima'             
 *      'subtotales'         
 */
$categoriasEnSubtotales = categoriasEnSubtotales($subtotales);

// dump($domCategoriasMonetizados,$nomCategoriasEstudio,$nomCategoriaMonetizado);
// dump($categoriasEnSubtotales);
?>
<style>
    .catExiste, .catNoExiste{
        padding: 12px 10px 10px 10px;
        margin: 7px;
        border: 1px solid #ccc;
        border-radius: 7px;
        width: 100%;
    }
    .catExiste{
        background: white;
    }
    .catNoExiste{
        background: #eee;
        height:50px;
    }
    .tituloCategoriaEstudio{
        font-size: 1.3em;
        font-weight: bold;
        color:orange;
    }
    .tituloMonetizado{
        font-weight:bold;
    }
    .subtotales{
        color:#666;
    }
</style>

@foreach( $domCategoriasMonetizados as $keyCatEstudio => $keysCatMonetizados )
    <div class='tableCss <?= (key_exists($keyCatEstudio,$categoriasEnSubtotales))? 'catExiste':'catNoExiste' ?>'>
        <div class='tableRow'>
            <div class='tableCell tituloCategoriaEstudio'>{{ $nomCategoriasEstudio[ $keyCatEstudio ] }}</div>
            <div class='tableCell'> </div>
        </div>
        @foreach( $keysCatMonetizados as $keyCatMonetizado )
            @foreach( $subtotales as $RendicionSubtotal )
                @if( $RendicionSubtotal->categoria_estudio == $keyCatEstudio && $RendicionSubtotal->categoria_monetizado == $keyCatMonetizado )
                    <div class='tableRow'>
                        <div class='tableCell tituloMonetizado subtotales'>
                            {{ $nomCategoriaMonetizado[ $keyCatMonetizado ] }}
                        </div>
                        <div class='tableCell subtotales'>
                                <?= $RendicionSubtotal->subtotal_en_sistema ?>
                        </div>
                    </div>
                @endif
            @endforeach
        @endforeach
    </div>
@endforeach


<?php
function categoriasEnSubtotales( $subtotales )
{
    $resultado = [];
    foreach( $subtotales as $RendicionSubtotal ){
        $resultado[ $RendicionSubtotal->categoria_estudio ][]= $RendicionSubtotal->categoria_monetizado;
    }
    return $resultado;
}