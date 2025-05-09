<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeatherSearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weather_searches', function (Blueprint $table) {
            $table->id();
            $table->string('city');
            $table->string('condition');
            $table->string('temperature');
            $table->string('humidity');
            $table->string('wind_kph');
            $table->string('local_time');
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weather_searches');
    }
}
