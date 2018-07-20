<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class CreateExportersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Config::get('ore.exporter.table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->integer('repository_id')->unsigned()->nullable();
            $table->foreign('repository_id')->references('id')->on(Config::get('ore.repository.table'));
            $table->text('input')->nullable();
            $table->string('filename');
            $table->text('body');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Config::get('ore.exporter.table'));
    }
}
