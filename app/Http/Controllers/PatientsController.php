<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Models\Patients;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PatientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    public function check_fill(Request $request) ///check of the fill data
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
            'national_number' => 'required|string|max:20|unique:patients,national_number',
            'gender' => 'required|in:male,female',
        ]);

 

        try {
            DB::beginTransaction();

            $user->update($validatedUser);

            
        

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


    /**
     * Display the specified resource.
     */
    public function show(Patients $patients) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patients $patients)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patients $patients) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $patient = Patients::where('id', $request->id)->first();
        //we have to check if the patient has any visit and we should cancel it 
        $patient->delete();
    }
}
