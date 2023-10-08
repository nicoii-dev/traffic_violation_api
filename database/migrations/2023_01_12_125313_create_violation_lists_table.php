<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ViolationCategory;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('violation_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('violation_categories_id')->unsigned()->nullable()->onDelete('set null');
            $table->string('violation_name');
            $table->string('penalty');
            $table->longText('description');
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
        Schema::dropIfExists('violation_lists');
    }
};
