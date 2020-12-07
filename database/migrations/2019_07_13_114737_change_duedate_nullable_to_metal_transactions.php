<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDuedateNullableToMetalTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::table('metal_transactions', function (Blueprint $table) {
                $table->dropColumn('due_date');
           
            });
            Schema::table('metal_transactions', function(Blueprint $table)
            {
                 $table->date('due_date')->nullable();
            });
      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('metal_transactions', function (Blueprint $table) {
            //
        });
    }
}
