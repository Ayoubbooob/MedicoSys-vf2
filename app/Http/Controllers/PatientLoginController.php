<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PatientLoginController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->only('ppr', 'password');

        if (Auth::guard('patient')->attempt($credentials)) {
            $patient = Auth::guard('patient')->user();
            if ($patient) {
                $patientData = [
                    'id' => $patient->id ?? 0,
                    'first_name' => $patient->first_name ?? '',
                    'last_name' => $patient->last_name ?? '',
                    'cin' => $patient->cin ?? '',
                    'num' => $patient->num ?? '',
                    'ppr' => $patient->ppr ?? '',
                ];


                Auth::guard('patient')->login($patient);
                $lifetime = $patient->getSessionTTL();

                return response()->json([
                    'id' => $patient->id,
                    'first_name' => $patient->first_name,
                    'last_name' => $patient->last_name,
                    'ppr' => $patient->ppr,
                    'num' => $patient->num,
                    'cin' => $patient->cin
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid login credentials',
        ], 401);
    }


    public function logout(Request $request)
    {
        Auth::guard('patient')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }
}
