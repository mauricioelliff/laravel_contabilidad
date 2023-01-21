<?php

namespace Modules\Contabilidad\Models\DB;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\DBModel;

class RendicionTotales extends DBModel
{
    use HasFactory;
    
    protected $table = 'yoga_rendiciones_totales';

    protected $fillable = [ 'rendicion_id',
                            'categoria_monetizado',
                            'total_en_sistema',
                            'total_rectificado'
                        ];
    
    
    protected static function newFactory()
    {
        return \Modules\Contabilidad\Database\factories\RendicionSubtotalFactory::new();
    }
}
