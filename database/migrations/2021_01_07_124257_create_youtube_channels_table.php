<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYoutubeChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('youtube_channels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('channel_name')->nullable();
            $table->string('channel_username')->nullable();
            $table->string('channel_id')->nullable();
            $table->bigInteger('subscribers')->nullable();
            $table->bigInteger('views_count')->nullable();
            $table->bigInteger('video_count')->nullable();
            $table->bigInteger('score')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('youtube_channels');
    }
}
