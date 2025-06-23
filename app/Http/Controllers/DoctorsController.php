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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      
    }

public function uploadSpecializationImage(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'name' => 'required|string|unique:specializations,name',
    ]);

    // استدعاء الكلاوديناري من الـ service container
    $cloudinary = app(Cloudinary::class);

    // رفع الصورة إلى Cloudinary
    $uploadedFile = $cloudinary->uploadApi()->upload(
        $request->file('image')->getRealPath(),
        ['folder' => 'specializations'] // يمكنك تعديل اسم المجلد كما تريد
    );

    $uploadedFileUrl = $uploadedFile['secure_url'];

    // حفظ في قاعدة البيانات
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
            'specialization'=>'required|string|max:255',
            'consultation_duration'=>'required|integer|min:1|max:1440',
            'user_id'=>'required'
    ]);
    $user= User::where('id',$validated['user_id'])->first();
    if (!$user) {
    return response()->json(['error' => 'User not found'], 404);
}
        $doctor = Doctors::create([
            'user_id'=> $user->id,
             'specialization'=>$validated['specialization'],
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
