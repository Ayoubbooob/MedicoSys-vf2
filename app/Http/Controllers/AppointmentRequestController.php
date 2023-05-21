<?php

namespace App\Http\Controllers;

use App\Models\patient;
use Illuminate\Http\Request;
use App\Models\AppointmentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AppointmentRequestController extends Controller
{
    public function create(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Retrieve the patient by patient_id
        $patient = Patient::find($request->input('patient_id'));

        // Check if the patient exists
        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found',
            ], 404);
        }

        // Create a new appointment request
        $appointmentRequest = AppointmentRequest::create([
            'patient_id' => $patient->id,
            'first_name' => $patient->first_name,
            'last_name' => $patient->last_name,
            'ppr' => $patient->ppr,
            'cin' => $patient->cin,
            'num' => $patient->num,
        ]);

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Appointment request created successfully',
            'appointment_request' => $appointmentRequest,
        ], 201);
    }


}

