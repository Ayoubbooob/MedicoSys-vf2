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
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('password');
            $table->string('password')->nullable();
            $table->decimal('last_imc', 8, 2)->nullable();


        });

        $patients = \App\Models\Patient::all();
        foreach ($patients as $patient) {
            $cin = $patient->cin;
            $yearOfBirth = date('Y', strtotime($patient->birth_date));
            $password = $cin . '@' . $yearOfBirth;
            $encryptedPassword = Hash::make($password);
            $patient->update(['password' => $encryptedPassword]);
        }

        foreach ($patients as $patient) {
            $lastImc = $patient->imcs()->latest()->value('bmi');
            $patient->update(['last_imc' => $lastImc]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            //
        });
    }
};
