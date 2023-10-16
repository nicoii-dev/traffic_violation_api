<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Violator;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citation_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unsigned()->nullable()->onDelete('set null');
            $table->foreignId('violator_id')->unsigned()->nullable()->onDelete('set null');
            $table->foreignId('license_id')->unsigned()->nullable()->onDelete('set null');
            $table->foreignId('vehicle_id')->unsigned()->nullable()->onDelete('set null');
            $table->string('violations');
            $table->string('tct')->nullable();
            $table->date('date_of_violation');
            $table->string('time_of_violation');
            $table->string('municipality');
            $table->string('zipcode');
            $table->string('barangay');
            $table->string('street');
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
        Schema::dropIfExists('citation_infos');
    }
};
