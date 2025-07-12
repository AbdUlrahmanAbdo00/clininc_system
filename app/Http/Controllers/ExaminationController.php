<?php

namespace App\Http\Controllers;

use App\Models\Analytics;
use App\Models\Appointment;
use App\Models\MedicalDiagnostic;
use App\Models\MedicalRecords;
use App\Models\MedicineSchedules;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Cloudinary\Cloudinary;

class ExaminationController extends Controller
{
    // This function to add an examination
    public function addExamin(Request $request) {
        $user = Auth::user();
        abort_unless($user, 404);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'diagnoses' => [
                'nullable',
                'array',
                function ($attributes, $value, $fail) {
                    foreach ($value as $diagnose) {
                        if(!isset($diagnose['diagnose_name']) || !isset($diagnose['desease_id']) || !isset($diagnose['diagnose_type'])) {
                            $fail('Each diagnose must contain : diagnose name, desease id, diagnose type.');
                        }
                    }
                }
            ],
            'diagnoses.*.diagnose_name' => 'required|string|max:255',
            'diagnoses.*.diagnose_type' => 'required|in:temporary,non-temporary',
            'diagnoses.*.description' => 'nullable|string',
            'analysiss' => [
                'nullable',
                'array',
                function ($attributes, $value, $fail) {
                    foreach ($value as $analysis) {
                        if(!isset($analysis['analysis_name']) || !isset($analysis['analysis_image'])) {
                            $fail('Each diagnose must contain : analysis name and image.');
                        }
                    }
                }
            ],
            'analysiss.*.analysis_name' => 'required|string|max:255',
            'analysiss.*.analysis_image' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048'
            ],
            'analysiss.*.description' => 'nullable|string',
            'medicines' => [
                'nullable',
                'array',
                function ($attributes, $value, $fail) {
                    foreach ($value as $medicine) {
                        if(!isset($medicine['medicine_id']) || !isset($medicine['rest_time']) || !isset($medicine['quantity'])) {
                            $fail('Each diagnose must contain : medicine id, rest time, quantity.');
                        }
                    }
                }
            ],
            'medicines.*.medicine_id' => 'required',
            'medicines.*.rest_time' => 'required|numeric|min:0',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.description' => 'nullable|string'
        ]);

        $validated['diagnoses'] = $validated['diagnoses'] ?? [];
        $validated['analysiss'] = $validated['analysiss'] ?? [];
        $validated['medicines'] = $validated['medicines'] ?? [];

        $now = Carbon::now();

        $appointment = null;
        
        $appointment = Appointment::where('doctor_id', $user->id)
        ->where('patient_id', $request['patient_id'])
        ->whereBetween('start_date', [
            $now->copy()->subHours(1)->toDateString(),
            $now->copy()->addHours(1)->toDateString()
        ])
        ->first();
        

        DB::transaction(function () use ($validated, $user, $now, &$appointment) {
            if (!$appointment) {
                $appointment = Appointment::create([
                    'doctor_id' => $user->id,
                    'patient_id' => $validated['patient_id'],
                    'date' => $now->toDateString(),
                    'start_date' => $now->toDateString(),
                    'end_date' => $now->toDateString(),
                    'finished' => 1,
                    'cancled' => false
                ]);
            }

            if ($validated['diagnoses']) {
                foreach($validated['diagnoses'] as $diagnose) {
                    Analytics::create([
                        'appointment_id' => $appointment->id,
                        'name' => $diagnose['diagnose_name'],
                        'desease_id' => $diagnose['desease_id'],
                        'type' => $diagnose['diagnose_type'],
                        'date' => Carbon::now(),
                        'description' => $diagnose['description'] ?? null,
                    ]);
                }
            }

            if ($validated['analysiss']) {
                $cloudinary = app(Cloudinary::class);

                foreach ($validated['analysiss'] as $analysisData) {
                    $file = $analysisData['analysis_image'];

                    $uploadedFile = $cloudinary->uploadApi()->upload(
                        $file->getRealPath(),
                        ['folder' => 'analysis_images']
                    );

                    $uploadedFileUrl = $uploadedFile['secure_url'];

                    //$imagePath = $analysisData['analysis_image']->store('analysis_images', 'public');
                
                    MedicalRecords::create([
                        'appointment_id' => $appointment->id,
                        'name' => $analysisData['analysis_name'],
                        'date' => Carbon::now(),
                        'image_path' => $uploadedFileUrl,
                        'description' => $analysisData['description'] ?? null,
                    ]);
                }   
            }

            if ($validated['medicines']) {
                foreach ($validated['medicines'] as $medicineData) {
                    MedicineSchedules::create([
                        'appointment_id' => $appointment->id,
                        'medicine_id' => $medicineData['medicine_id'],
                        'rest_time' => $medicineData['rest_time'],
                        'quantity' => $medicineData['quantity'],
                        'description' => $medicineData['description'] ?? null,
                    ]);
                }
            }
        });

        $appointment->finished = 1;

        return response()->json([
            'success' => 'the addition has completed successfully'
        ]);
    }
}
