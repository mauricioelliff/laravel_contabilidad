<?php

namespace App\Modules\Contabilidad\Models\DB;

//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\DBModel;

class CuentaCorriente extends DBModel
{
    use HasFactory;
    
    protected $table = 'yoga_cuentas_corrientes';

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Contabilidad\Database\factories\CuentaCorrienteFactory::new();
    }
}
