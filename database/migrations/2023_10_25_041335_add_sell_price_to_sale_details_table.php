<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sale_details', function (Blueprint $table) {
            $table->integer('buy_price');
        });
    }

    public function down()
    {
        Schema::table('sale_details', function (Blueprint $table) {
            $table->dropColumn('buy_price');
        });
    }
};
