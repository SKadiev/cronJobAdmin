<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('embed');
            $table->string('title');
            $table->string('url');
            $table->string('published_by');
            $table->string('video_uuid');
            $table->bigInteger('views')->nullable();
            $table->bigInteger('comments')->nullable();
            $table->bigInteger('likes')->nullable();
            $table->bigInteger('dislikes')->nullable();
            $table->bigInteger('favorite')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
