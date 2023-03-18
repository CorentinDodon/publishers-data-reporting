<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reporting_data', function (Blueprint $table) {
            $table->foreign(['publisher_id'], 'reporting_data_ibfk_1')->references(['id'])->on('publisher')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reporting_data', function (Blueprint $table) {
            $table->dropForeign('reporting_data_ibfk_1');
        });
    }
};
