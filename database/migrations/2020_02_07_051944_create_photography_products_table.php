<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotographyProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photography_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sku');
            $table->string('color');
            $table->string('regular_shoot_status');
			  $table->string('model_shoot_status');
          
		    $table->string('instagram_shoot_status');
          
		    $table->string('category_id');
          
		    $table->string('created_by');
          
            $table->Integer('deleted_at');
            $table->Integer('status');
            $table->Integer('userid');
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
        Schema::dropIfExists('photography_products');
    }
}
