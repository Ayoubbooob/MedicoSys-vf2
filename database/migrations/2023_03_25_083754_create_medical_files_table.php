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
        Schema::create('medical_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->unsigned();
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->string('ppr')->unique();
            $table->json('dynamic_fields');
//            $table->json('antecedents')->nullable();
//            $table->json('biometrie')->nullable();
//            $table->text('traitement_chronique')->nullable();
//            $table->json('vaccination')->nullable();
//            $table->json('examen_biologiques')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_files');
    }
};
