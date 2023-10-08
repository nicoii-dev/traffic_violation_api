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
        Schema::create('community_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('citation_id')->unsigned()->nullable()->onDelete('set null');
            $table->foreignId('invoice_id')->unsigned()->nullable()->onDelete('set null');
            $table->foreignId('community_service_details_id')->unsigned()->nullable()->onDelete('set null');
            $table->string('rendered_time');
            $table->string('status');
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
        Schema::dropIfExists('community_services');
    }
};
