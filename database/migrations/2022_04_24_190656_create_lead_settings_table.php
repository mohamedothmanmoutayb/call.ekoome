<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('time_no_answer')->nullable();
            $table->string('number_tentative')->nullable();
            $table->string('tentative_per_day')->nullable();
            $table->text('meaasge_whatsapp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_settings');
    }
}
