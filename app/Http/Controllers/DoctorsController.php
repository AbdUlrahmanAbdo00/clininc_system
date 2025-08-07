<?php

namespace App\Http\Controllers;

use App\Models\Doctors;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Cloudinary\Cloudinary;
use Stichoza\GoogleTranslate\GoogleTranslate;

class DoctorsController extends Controller
{
    public function getAllSpecializations(Request $request)
    {
        $lang = $request->header('lan', 'en');
        $specializations = Specialization::all(['id', 'name', 'path']);

        if ($specializations->isEmpty()) {
            $message = (new GoogleTranslate($lang))->translate('No specializations found.');
            return response()->json([
                'success' => false,
                'message' => $message,
                'data' => []
            ]);
        }

        $formatted = $specializations->map(fn($s) => [
            'id' => (string) $s->id,
            'name' => $s->name,
            'iconUrl' => $s->path,
        ]);

        $message = (new GoogleTranslate($lang))->translate('Specialities fetched successfully.');
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $formatted,
        ]);
    }

    public function getDoctorsBySpecialization(Request $request, $specializationId)
    {
        $lang = $request->header('lan', 'en');
        $doctors = Doctors::with('specialization')
            ->where('specialization_id', $specializationId)
            ->get();

        $formattedDoctors = $doctors->map(function ($doctor) {
            $user = User::find($doctor->user_id);
            $spec = Specialization::find($doctor->specialization_id);
            return [
                'id' => (string) $doctor->id,
                'imageUrl' => $doctor->imageUrl,
                'name' => $user->first_name ?? 'Unknown',
                'bio' => $doctor->bio,
                'speciality' => [
                    'id' => (string) $spec->id,
                    'name' => $spec->name,
                    'iconUrl' => $spec->path
                ]
            ];
        });

        $message = (new GoogleTranslate($lang))->translate('Doctors fetched successfully.');
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $formattedDoctors
        ]);
    }

    public function uploadSpecializationImage(Request $request)
    {
        $lang = $request->header('lan', 'en');
        $translator = new GoogleTranslate($lang);

        $request->validate([
            'image' => 'required|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg+xml|max:2048',
            'name' => 'required|string|unique:specializations,name',
        ]);

        $cloudinary = app(Cloudinary::class);
        $uploaded = $cloudinary->uploadApi()->upload(
            $request->file('image')->getRealPath(),
            ['folder' => 'specializations']
        );
        $url = $uploaded['secure_url'];

        Specialization::create([
            'name' => $request->name,
            'path' => $url,
        ]);

        return response()->json([
            'success' => true,
            'message' => $translator->translate('The image was uploaded successfully.'),
            'data' => ['imageUrl' => $url]
        ]);
    }

    public function uploadDoctorImage(Request $request)
    {
        $lang = $request->header('lan', 'en');
        $translator = new GoogleTranslate($lang);

        $request->validate([
            'image' => 'required|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg+xml|max:2048',
            'doctor_id' => 'required|integer|exists:doctors,id',
        ]);

        $cloudinary = app(Cloudinary::class);
        $uploaded = $cloudinary->uploadApi()->upload(
            $request->file('image')->getRealPath(),
            ['folder' => 'Doctors']
        );
        $url = $uploaded['secure_url'];

        $doctor = Doctors::find($request->doctor_id);
        if (!$doctor) {
            return response()->json([
                'success' => false,
                'message' => $translator->translate('Doctor not found.')
            ], 404);
        }

        $doctor->imageUrl = $url;
        $doctor->save();

        return response()->json([
            'success' => true,
            'message' => $translator->translate('The image was uploaded successfully.'),
            'data' => ['imageUrl' => $url]
        ]);
    }

    public function store(Request $request)
    {
        $lang = $request->header('lan', 'en');
        $translator = new GoogleTranslate($lang);

        $user = Auth::user();
        $validated = $request->validate([
            'specialization_id' => 'required|exists:specializations,id',
            'consultation_duration' => 'required|integer|min:1|max:1440',
            'user_id' => 'required|exists:users,id',
            'bio' => 'required|string'
        ]);

        $user = User::find($validated['user_id']);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => $translator->translate('User not found')
            ], 404);
        }

        if (!$user->hasRole('doctor')) {
            $user->assignRole('doctor');
        }

        $defaultPath = "https://res.cloudinary.com/.../specializations/default.png";
        if ($user->gender === 'female') {
            $defaultPath = "https://res.cloudinary.com/.../specializations/default_female.png";
        }

        $doctor = Doctors::updateOrCreate(
            ['user_id' => $user->id],
            [
                'specialization_id' => $validated['specialization_id'],
                'consultation_duration' => $validated['consultation_duration'],
                'bio' => $validated['bio'],
                'imageUrl' => $defaultPath
            ]
        );

        return response()->json([
            'success' => true,
            'message' => $translator->translate('Doctor data saved successfully')
        ]);
    }

    public function getDoctorById(Request $request, $id)
    {
        $lang = $request->header('lan', 'en');
        $translator = new GoogleTranslate($lang);

        $doctor = Doctors::find($id);
        $user = User::find($doctor->user_id ?? null);
        $spec = Specialization::find($doctor->specialization_id ?? null);

        if (!$doctor || !$user) {
            return response()->json([
                'success' => false,
                'message' => $translator->translate('user not found.')
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => $translator->translate('user data retrieved successfully.'),
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
                'escape_counter' => $doctor->escape_counter,
                'bio' => $doctor->bio,
                'imageUrl' => $doctor->imageUrl,
                'consultation_duration' => $doctor->consultation_duration,
                'specialization' => $spec->name ?? 'Unknown'
            ]
        ]);
    }

    public function getDoctorByToken(Request $request)
    {
        $lang = $request->header('lan', 'en');
        $translator = new GoogleTranslate($lang);

        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => $translator->translate('Unauthorized.')
            ], 401);
        }

        $doctor = Doctors::where('user_id', $user->id)->first();
        if (!$doctor) {
            return response()->json([
                'success' => false,
                'message' => $translator->translate('Doctor not found for this user.')
            ], 404);
        }

        $spec = Specialization::find($doctor->specialization_id);

        return response()->json([
            'success' => true,
            'message' => $translator->translate('User data retrieved successfully.'),
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
                'escape_counter' => $doctor->escape_counter,
                'bio' => $doctor->bio,
                'imageUrl' => $doctor->imageUrl,
                'consultation_duration' => $doctor->consultation_duration,
                'specialization' => $spec->name ?? 'Unknown'
            ]
        ]);
    }

    public function deleteSpecialization(Request $request, $id)
    {
        $lang = $request->header('lan', 'en');
        $translator = new GoogleTranslate($lang);

        $spec = Specialization::find($id);
        if (!$spec) {
            return response()->json([
                'success' => false,
                'message' => $translator->translate('Specialization not found.')
            ], 404);
        }

        $spec->delete();

        return response()->json([
            'success' => true,
            'message' => $translator->translate('Specialization deleted successfully.')
        ]);
    }
}
