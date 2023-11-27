<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ViolationCategory;
use Illuminate\Support\Facades\DB;

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

        // Insert some stuff
        DB::table('violation_lists')->insert(
            array(
                    'id' => '1',
                    'violation_categories_id' => '1',
                    'violation_name' => 'No helmet',
                    'penalty' => '1000',
                    'description' => 'No helmet violation',
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
        Schema::dropIfExists('violation_lists');
    }
};
