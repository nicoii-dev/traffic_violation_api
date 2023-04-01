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
        Schema::create('community_service_details', function (Blueprint $table) {
            $table->id();
            $table->string('service_name');
            $table->string('discount');
            $table->string('time_to_render');
            $table->timestamps();
        });

            // // Insert some stuff
            // DB::table('community_service_details')->insert(
            //     array(
            //         'id' => '1',
            //         'service_name' => 'Community Service',
            //         'discount' => '10',
            //         'time_to_render' => '8',
            //     )
            // );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('community_service_details');
    }
};
