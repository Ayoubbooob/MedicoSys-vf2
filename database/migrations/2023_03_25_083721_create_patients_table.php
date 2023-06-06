<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('cin')->unique();
            $table->string('num')->unique();;
            //$table->string('password');
            $table->string('gender')->nullable();
            $table->string('email')->nullable();
            $table->string('marital_status')->nullable();
            $table->date('birth_date')->nullable();
            $table->decimal('last_imc', 8, 2)->nullable();
            $table->string('password')->nullable();
            $table->timestamps();

        });

        // Update the "last_imc" column with the last recorded BMI value for each patient
        $patients = \App\Models\Patient::all();
        foreach ($patients as $patient) {
            $lastImc = $patient->imcs()->latest()->value('bmi');
            $patient->update(['last_imc' => $lastImc]);
        }

        //$patients = \App\Models\Patient::all();
        foreach ($patients as $patient) {
            $cin = $patient->cin;
            $yearOfBirth = date('Y', strtotime($patient->birth_date));
            $password = $cin . '@' . $yearOfBirth;
            $encryptedPassword = Hash::make($password);
            $patient->update(['password' => $encryptedPassword]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
