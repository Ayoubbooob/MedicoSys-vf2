<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\IMC;
use App\Models\patient;
use Illuminate\Http\Request;

class IMCController extends Controller
{
    public function store(Request $request, $patient_id)
    {
        // Validate input data
        $validatedData = $request->validate([
            'weight' => 'required|numeric',
            'size' => 'required|numeric',
            'gender' => 'required|in:male,female',
        ]);

        // Create a new IMC record for the patient
        $imc = new IMC();
        $imc->weight = $validatedData['weight'];
        $imc->size = $validatedData['size'];
        $imc->gender = $validatedData['gender'];
        $imc->patient_id = $patient_id;
        $imc->save();

        // Return a success response
        return response()->json(['message' => 'IMC data stored successfully']);
    }

    public function index($patient_id)
    {
        $patient = patient::find($patient_id);

        if (!$patient) {
            return response()->json(['error' => 'Patient not found.'], 404);
        }

        $imcs = $patient->imcs;

        return response()->json(['data' => $imcs]);
    }
}
