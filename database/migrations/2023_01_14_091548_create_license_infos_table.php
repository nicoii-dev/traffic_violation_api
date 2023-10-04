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
        Schema::create('license_infos', function (Blueprint $table) {
            $table->id();
            $table->string('violator_id');
            $table->string('license_number')->unique()->nullable();
            $table->string('license_type')->nullable();
            $table->string('license_status')->nullable();
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
        Schema::dropIfExists('license_infos');
    }
};
