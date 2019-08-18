<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateImpostoEncargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imposto_encargos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('descricao')->nullable();
            $table->string('percentagem')->nullable();
            $table->string('encargo')->nullable();
            $table->string('data')->nullable();
            $table->string('montante')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('imposto_encargos');
    }
}
