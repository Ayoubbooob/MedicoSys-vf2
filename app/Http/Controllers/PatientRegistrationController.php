<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;


class PatientRegistrationController extends Controller
{
    public function register(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'num' => 'required|string|max:255',
            'cin' => 'required|string|max:255|unique:patients',
            'ppr' => 'required|string|max:255|unique:patients',
            'password' => 'required|string|min:6',
        ]);

        // If validation fails, return a JSON response with validation errors
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Create a new patient
        $patient = Patient::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'num' => $request->input('num'),
            'cin' => $request->input('cin'),
            'ppr' => $request->input('ppr'),
            'password' => Hash::make($request->input('password')),
        ]);

        // Return a JSON response with success message and patient data
        return response()->json([
            'message' => 'Registration successful.',
            'data' => $patient
        ], 201);
    }
}
