<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->foreign('doctor_id')
                ->references('id')
                ->on('doctors')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            //
        });
    }
};
