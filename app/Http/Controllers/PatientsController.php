<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Models\Patients;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PatientsController extends Controller
{



public function getAuthenticatedPatientData()
{
    $user = Auth::user();


    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not authenticated.'
        ], 401);
    }

   
    $patient = Patients::where('user_id', $user->id)->first();

    if (!$patient) {
        return response()->json([
            'success' => false,
            'message' => 'Patient data not found.'
        ], 404);
    }

    // إرجاع البيانات
    return response()->json([
        'success' => true,
        'message' => 'User data retrieved successfully.',
        'data' => [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'middle_name' => $user->middle_name,
            'last_name' => $user->last_name,
            'number' => $user->number,
            'mother_name' => $user->mother_name,
            'birth_day' => $user->birth_day,
            'national_number' => $user->national_number,
            'gender' => $user->gender,
            'daily_doses_number' => $patient->daily_doses_number,
            'taken_doses' => $patient->taken_doses,
        ]
    ]);
}


    public function getPatientById($id)
    {
        $patient = Patients::where('id', $id)->first();

        $user = User::findOr($id, function () {
            return null;
        });

        if (!$user || !$patient) {
            return response()->json([
                'success' => false,
                'message' => 'user not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'user data retrieved successfully.',
            'data' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'middle_name' => $user->middle_name,
                'last_name' => $user->last_name,
                'number' => $user->number,
                'mother_name' => $user->mother_name,
                'birth_day' => $user->birth_day,
                'national_number' => $user->national_number,
                'gender' => $user->gender,
                'daily_doses_number' => $patient->daily_doses_number,
                'taken_doses' => $patient->taken_doses,

            ]
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    public function check_fill(PatientRequest $request) ///check of the fill data
    {
        $user = Auth::user();
        $user = User::where('id', $user->id)->first();
        $patient = Patients::where('user_id', $user->id)->first();
        if ($patient) {
            return response()->json(['success' => 'The data already exists.'], 200);
        } else {
            return response()->json(['error' => 'you have to fill data.'], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        $user = Auth::user();
        $user = User::where('id', $user->id)->first();

        $request->merge([
            'user_id' => $user->id,
        ]);

        $validatedUser = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'birth_day' => 'required|date|before:today',
            'national_number' => 'required|string|max:20|unique:users,national_number',
            'gender' => 'required|in:male,female',
        ]);



        try {
            DB::beginTransaction();

            $user->update($validatedUser);

            $alreadyExists = Patients::where('user_id', $user->id)->exists();
            if (!$alreadyExists) {
                Patients::create([
                    'user_id' => $user->id
                ]);
            }

              if (!$user->hasRole('patient')) {
                $user->assignRole('patient');
            }
            DB::commit();
          
            return response()->json([
                'success' => true,
                'message' => 'Patient created successfully.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


  
    public function destroy(Request $request)
    {
        $patient = Patients::where('id', $request->id)->first();
        //we have to check if the patient has any visit and we should cancel it 
        $patient->delete();
    }
}
