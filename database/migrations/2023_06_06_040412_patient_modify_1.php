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
        Schema::table('patients', function (Blueprint $table) {
            // Add the "last_imc" column
            $table->decimal('last_imc', 8, 2)->nullable();
        });

        // Update the "last_imc" column with the last recorded BMI value for each patient
        $patients = \App\Models\Patient::all();
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
