<?php

namespace App\Http\Controllers;
use App\Models\Doctors;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Cloudinary\Cloudinary;
class DoctorsController extends Controller
{

public function getAllSpecializations(Request $request)
{
    $specializations = Specialization::all(['id', 'name', 'path']); 

    if ($specializations->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No specializations found.',
            'data' => []
        ]);
    }

    $formatted = $specializations->map(function ($specialization) {
        return [
            'id' => (string) $specialization->id,
            'name' => $specialization->name,
            'iconUrl' => $specialization->path, 
        ];
    });

    return response()->json([
        'success' => true,
        'message' => 'Specialities fetched successfully.',
        'data' => $formatted,
    ]);
}


public function getDoctorsBySpecialization($specializationId)
{
    $doctors = Doctors::with('specialization')
        ->where('specialization_id', $specializationId)
        ->get();

    $formattedDoctors = $doctors->map(function ($doctor) {
        $user = User::where('id',$doctor->user_id)->first();
        $specialization = Specialization::where('id',$doctor->specialization_id)->first();
        return [
            'id' => (string) $doctor->id,
            'imageUrl' => $doctor->image_url,
            'name' => $user->first_name,
            'bio' => $doctor->bio,
            'speciality' => [
                'id' => (string)  $specialization->id,
                'name' =>  $specialization->name,
                'iconUrl' =>  $specialization->path, 
            ],
        ];
    });

    return response()->json([
        'success' => true,
        'message' => 'Doctors fetched successfully.',
        'data' => $formattedDoctors,
    ]);
}

public function uploadSpecializationImage(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'name' => 'required|string|unique:specializations,name',
    ]);

    $cloudinary = app(Cloudinary::class);

 
    $uploadedFile = $cloudinary->uploadApi()->upload(
        $request->file('image')->getRealPath(),
        ['folder' => 'specializations'] 
    );

    $uploadedFileUrl = $uploadedFile['secure_url'];

  
    Specialization::create([
        'name' => $request->name,
        'path' => $uploadedFileUrl,
    ]);

    return response()->json([
        'success' => true,
        'image_url' => $uploadedFileUrl,
    ]);
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
        // $user = User::where('id', $user->id)->first();
        
        $validated= $request->validate( [
            'specialization_id'=>'required|exists:specializations,id',
            'consultation_duration'=>'required|integer|min:1|max:1440',
            'user_id'=>'required'
    ]);
    $user= User::where('id',$validated['user_id'])->first();
    if (!$user) {
    return response()->json(['error' => 'User not found'], 404);
}
        $doctor = Doctors::create([
            'user_id'=> $user->id,
             'specialization_id'=>$validated['specialization_id'],
             'consultation_duration'=>$validated['consultation_duration']
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
