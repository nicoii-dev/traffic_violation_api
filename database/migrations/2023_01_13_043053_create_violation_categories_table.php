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
        Schema::create('violation_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category_name');
            $table->timestamps();
        });

        // Insert some stuff
        DB::table('violation_categories')->insert(
            array(
                    'id' => '1',
                    'category_name' => 'Person',
            ),
        );

        // Insert some stuff
        DB::table('violation_categories')->insert(
            array(
                    'id' => '2',
                    'category_name' => 'Vehicle',
            ),
        );
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('violation_categories');
    }
};
