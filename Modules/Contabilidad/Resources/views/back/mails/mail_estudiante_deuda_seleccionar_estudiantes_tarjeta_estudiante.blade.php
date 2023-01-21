<div class='tarjeta_deudas'>

    @if( !isset($conCheck) || $conCheck==true )
        <div class='deuda_check'>
            <input type='checkbox' name='check_estudiante' id='{{ $Estudiante->dni }}' />
        </div>
    @endif
    <div style='display:inline-block'>
        <div class='deuda_nombres'>{{ $Estudiante->apellido.' '.$Estudiante->nombres }}</div>
        <br>
        @if( key_exists( $Estudiante->dni, $info['mails'] ) )
            <div style='display:block'>
                <div class='enviados'>Enviados este mes: {{ $info['mails'][$Estudiante->dni]['cantidad']}}</div>
                <div class='ultimo'>Último {{ substr( $info['mails'][$Estudiante->dni]['ultimo'], 0, 10) }} </div>
                <div style='clear:both'> </div>
            </div>
        @endif
        @foreach( $deudas as $cursoNombre => $stringDeudas )
            @if( $cursoNombre == 'total' ) 
                @continue;
            @endif
            <div class='deuda_estudiante'>{{$cursoNombre.': '.$stringDeudas }}</div>
        @endforeach

        <div class="deuda_total_estudiante"><span class='string_deuda'>Deuda </span>{{ $deudas['total'] }}</div>
    </div>
</div>