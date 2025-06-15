<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('modules', function (Blueprint $table) {
        $table->unsignedBigInteger('trainer_id')->nullable()->after('id');

        // If you want foreign key constraint:
        $table->foreign('trainer_id')->references('id')->on('users')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('modules', function (Blueprint $table) {
        $table->dropForeign(['trainer_id']);
        $table->dropColumn('trainer_id');
    });
}
};
