<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdLastmileToErrorsTable extends Migration
{
   

        public function up()
        {
            Schema::table('errors', function (Blueprint $table) {
                $table->unsignedBigInteger('id_lastmile')->nullable()->after('id_lead');
            });
        }

        public function down()
        {
            Schema::table('errors', function (Blueprint $table) {
                $table->dropColumn('id_lastmile');
            });
        }


}

