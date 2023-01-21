<?php // INPUT:  $MailComponentes   ?>
<?php // el envío de mails en Laravel, elimina todo lo que es CSS no directo en un style.
        // Para agregar estilos, puedo hacerlo. Ver config/mail.php
?>
<body>

    <p align='left'>
        <img src='https://www.escueladenathayoga.com.ar/templates/5-om2021/logos/logohome.png' />
    </p>

    <p class='saludo_inicial' style='color:#e85112;font-weight:bold;'>
        {{ reemplazarPatrones( $MailComponentes->saludo_inicial, $MailComponentes->patrones ) }}
    </p>

    <p class='texto'>
        <?php 
        echo reemplazarPatrones( $MailComponentes->texto, $MailComponentes->patrones ); 
        echo ( isset($MailComponentes->deudas)? textoDeudas( $MailComponentes->deudas, $conTotal=false ) : '' ); 
        ?>
    </p>

    <p class='saludo_final' style='color:#e85112;font-weight:bold;'>
        <?php echo reemplazarPatrones( $MailComponentes->saludo_final, $MailComponentes->patrones ) ?>
    </p>
    
</body>    
