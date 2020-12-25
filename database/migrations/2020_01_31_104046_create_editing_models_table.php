<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditingModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('editing_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->Integer('product_id');
            $table->Integer('category_id');
            $table->Integer('status');
            $table->Integer('current_status');
            $table->Integer('next_department_status');
            $table->Integer('work_assign_by');
            $table->Integer('work_assign_user');
            $table->Integer('created_by');
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
        Schema::dropIfExists('editing_models');
    }
}
