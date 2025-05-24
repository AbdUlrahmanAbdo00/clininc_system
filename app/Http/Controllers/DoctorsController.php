<?php

namespace App\Http\Controllers;

use App\Models\Doctors;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        // dd($user);
        $user = User::where('id', $user->id)->first();
        $validated= $request->validate( ['specialization'=>'required|string|max:255']);
        $doctor = Doctors::create([
            'user_id'=> $user->id,
             'specialization'=>$validated['specialization']
        ]);
        return response()->json(['success' => 'doctor created successfly'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctors $doctors)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctors $doctors)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctors $doctors)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctors $doctors)
    {
        //
    }
}
