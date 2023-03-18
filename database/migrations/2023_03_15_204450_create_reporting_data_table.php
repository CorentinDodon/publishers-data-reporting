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
        Schema::create('reporting_data', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->decimal('requests', 10, 0)->nullable()->default(0);
            $table->decimal('impressions', 10, 0)->nullable()->default(0);
            $table->decimal('clicks', 10, 0)->nullable()->default(0);
            $table->decimal('revenues', 10)->nullable()->default(0);
            $table->dateTime('report_date');
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();
            $table->bigInteger('publisher_id')->index('publisher_id');
            $table->index('report_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reporting_data');
    }
};
