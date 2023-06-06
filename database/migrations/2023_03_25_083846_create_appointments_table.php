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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_file_id')->unsigned();
            $table->foreignId('doctor_id')->unsigned();
            $table->dateTime('appointment_date');
            $table->foreign('medical_file_id')->references('id')->on('medical_files')->onDelete('cascade');
            $table->foreignId('doctor_id')->unsigned();
            $table->dateTime('appointment_date');
            $table->string('motif')->nullable();
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
            $table->string('status');
            $table->json('informations_supplementaires');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
