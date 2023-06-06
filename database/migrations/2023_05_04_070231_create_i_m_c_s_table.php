<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('i_m_c_s', function (Blueprint $table) {
            $table->id();
            $table->float('weight');
            $table->float('size');
            $table->string('gender');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->decimal('bmi', 8, 2)->nullable();

            $table->timestamps();
        });

        DB::statement('UPDATE i_m_c_s SET bmi = weight / (size * size)');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('i_m_c_s');
    }
};
