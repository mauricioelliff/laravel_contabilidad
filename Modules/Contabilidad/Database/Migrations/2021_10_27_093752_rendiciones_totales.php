<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RendicionesTotales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yoga_rendiciones_totales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rendicion_id'); 
            $table->string('categoria_monetizado',50)->index();  // matricula, cuota, examen, mes,  EN SINGULAR en formato url
            $table->integer('total_en_sistema');
            $table->integer('total_rectificado')->nullable();  // Solo si difiere de sistema

            $table->foreign('rendicion_id', 'fk_rendiciones_totales_TO_rendiciones' )
                    ->references('id')->on('yoga_rendiciones')
                    ->onDelete('restrict')->onUpdate('cascade');
            
            // Comienza con "i_" + nombre de tabla + índice de campos
            $table->index(  ['rendicion_id', 'categoria_monetizado' ], 
                            'i_id_categoria_estudio_categoria_monetizado' )->unique();
            
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
