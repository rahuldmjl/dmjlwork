<?php

use Illuminate\Database\Seeder;

class PhotographyProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
	*/
    public function run()
    {
        DB::unprepared(file_get_contents(__DIR__."/dml_photography_products.sql"));
    }
}
