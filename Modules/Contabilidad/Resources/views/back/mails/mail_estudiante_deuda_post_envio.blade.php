<?php
$MailComponentes = $info['MailComponentes'];
if( count($info['estudiantes'])==0 ){
    echo '<div class="warning">No marcaste a nadie</div>';
    return;
}
?>
    <style>
        .saludo_inicial{
        }
        .texto{
        }
        .saludo_final{
        }
        .total{
            font-weight:bold;
        }
    </style>

<div align='center'>
    <table>
        <tr>
            <td>
                <img src='{{ __LARAVEL_BASE__ }}/imagenes/sistema/success.png' />
            </td>
            <td><h1>Listo!</h1></td>
        </tr>
        <tr>
            <td colspan='2'>
                <h3>{{ count($info['estudiantes']) }} mail/s enviado/s.</h3>
            </td>
        </tr>
        <tr>
            <td colspan='2'>
                <h3>Este es el último:</h3>
            </td>
        </tr>
    </table>    
    
    <div class='bloque-con-elementos' 
         style='display:inline-block;text-align:left;max-width:600px;padding:10px'>
        <?php $MailComponentes = $info['MailComponentes'] ?>
        @include('contabilidad::mails.estudiantedeudaBODY')
    </div>
</div>
