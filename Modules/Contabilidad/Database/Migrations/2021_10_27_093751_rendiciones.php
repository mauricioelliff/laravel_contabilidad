<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Rendiciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yoga_rendiciones', function (Blueprint $table) {
            $table->id();
            $table->integer('sedes_id')->index();    //unsignedBigInteger('sedes_id')->index(); 
            $table->string('categoria_estudio',50)->index();  // formacion, reiki, taller, plataforma, EN SINGULAR en formato url
            $table->date('fecha_desde');    // día incluídos
            $table->date('fecha_hasta');    // día incluídos
            $table->boolean('central_recibio_dinero');
            $table->boolean('central_reviso_rendicion');
            $table->string('observaciones_de_la_sede',255)->nullable();
            $table->string('observaciones_de_central',255)->nullable();

            $table->timestamp('created_at')->useCurrent();  // la sede crea la rendición
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));            

            
            $table->index(  ['sedes_id', 'fecha_desde', 'categoria_estudio' ], 
                            'i_id_categoria_estudio_categoria_monetizado' )->unique();
            
            $table->foreign('sedes_id', 'fk_rendiciones_TO_sedes' )
                    ->references('id_sede_centro')->on('sedes_centros')
                    ->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
