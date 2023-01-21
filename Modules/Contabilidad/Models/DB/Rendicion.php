<?php

namespace Modules\Contabilidad\Models\DB;

//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\DBModel;

class Rendicion extends DBModel
{
    //use HasFactory;
    
    protected $table = 'yoga_rendiciones';

    protected $fillable = [ 'sedes_id',
                            'fecha_desde',
                            'fecha_hasta',
                            'categoria_estudio',
                            'observaciones_de_la_sede',
                            'observaciones_de_central',
                            'central_recibio_dinero',
                            'central_reviso_rendicion',
                        ];
    
    protected static function newFactory()
    {
        return \Modules\Contabilidad\Database\factories\RendicionFactory::new();
    }
    
    private const dominioCategoriasEstudioYSusMonetizados = [
        'formacion'     => [ 'matricula', 'cuota', 'examen' ],
        'reiki'         => [ 'matricula', 'cuota', 'examen' ],
        'taller'        => [ 'matricula' ],
        'plataforma'    => [ 'mes' ],
        'clases'        => [ 'mes' ],
    ];
    private const nombresCategoriaEstudio = [
                            'formacion'     => 'Formación, Niveles 1 a 5, y Egresados',
                            'reiki'         => 'Reiki',
                            'taller'        => 'Talleres',
                            'plataforma'    => 'Plataforma',
                            'clases'        => 'Clases presenciales'
                        ];
    private const nombresCategoriaMonetizado = [
                            'matricula'     => 'Matrículas',
                            'cuota'         => 'Cuotas',
                            'examen'        => 'Examenes',
                            'mes'           => 'Mes',
                        ];
    
    public static function dominioCategoriasEstudioYSusMonetizados(){
        return self::dominioCategoriasEstudioYSusMonetizados;
    }
    public static function nombresCategoriaEstudio(){
        return self::nombresCategoriaEstudio;
    }
    public static function nombresCategoriaMonetizado(){
        return self::nombresCategoriaMonetizado;
    }

    
    public static function ultimaYSubtotales( $sedes_id=null )
    {
        $ultima = self::ultima( $sedes_id );
        $subtotales = self::totales( $ultima );
        return [ 'ultima'=>$ultima, 'subtotales'=>$subtotales ];
    }
    
    public static function ultima( $sedes_id=null )
    {
        $resultado = self::select()->orderBy('fecha_hasta', 'DESC');    //->limit(1);
        if( $sedes_id ){
            $resultado->where('sedes_id','=',$sedes_id );
        }
        return $resultado->first();
    }
    
    public static function entreFechas( DateTime $fechaDesde, DateTime $fechaHasta )
    {
        
    }
    
    public static function totales( Rendicion $Rendicion )
    {
        return $Rendicion->hasMany( RendicionTotales::class )->get();
    }
    
    
}
