<?php

namespace App\Http\Controllers;

use App\Models\Analytics;
use App\Models\Appointment;
use App\Models\Doctors;
use App\Models\MedicalRecords;
use App\Models\MedicineSchedules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Log;

class ExaminationController extends Controller
{
    // هذه الدالة لإضافة فحص جديد
    public function addExamin(Request $request) {
        $user = Auth::user();
        abort_unless($user, 404);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'diagnoses' => [
                'nullable', 'array',
                function ($attributes, $value, $fail) {
                    foreach ($value as $diagnose) {
                        if (!isset($diagnose['diagnose_name']) || !isset($diagnose['diagnose_type'])) {
                            $fail('Each diagnose must contain: diagnose name, diagnose type.');
                        }
                    }
                }
            ],
            'diagnoses.*.diagnose_name' => 'required|string|max:255',
            'diagnoses.*.diagnose_type' => 'required|in:temporary,non-temporary',
            'diagnoses.*.description' => 'nullable|string',
            'analysiss' => [
                'nullable', 'array',
                function ($attributes, $value, $fail) {
                    foreach ($value as $analysis) {
                        if (!isset($analysis['analysis_name']) || !isset($analysis['analysis_image'])) {
                            $fail('Each analysis must contain: analysis name and image.');
                        }
                    }
                }
            ],
            'analysiss.*.analysis_name' => 'required|string|max:255',
            'analysiss.*.analysis_image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'analysiss.*.description' => 'nullable|string',
            'medicines' => [
                'nullable', 'array',
                function ($attributes, $value, $fail) {
                    foreach ($value as $medicine) {
                        if (!isset($medicine['medicine_id']) || !isset($medicine['rest_time']) || !isset($medicine['quantity'])) {
                            $fail('Each medicine must contain: medicine id, rest time, quantity.');
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
        $doctor = Doctors::where('user_id', $user->id)->first();

        $appointment = Appointment::where('doctor_id', $doctor->id)
            ->where('patient_id', $request['patient_id'])
            ->whereBetween('start_date', [$now->copy()->subHours(1), $now->copy()->addHours(1)])
            ->first();

        try {
            DB::beginTransaction();

            if (!$appointment) {
                $appointment = Appointment::create([
                    'doctor_id' => $doctor->id,
                    'patient_id' => $validated['patient_id'],
                    'date' => $now,
                    'start_date' => $now,
                    'end_date' => $now,
                    'finished' => 1,
                    'cancled' => null
                ]);
                Log::info("Appointment created: {$appointment->id}");
            }

            foreach ($validated['diagnoses'] as $diagnose) {
                $analytics = Analytics::create([
                    'appointment_id' => $appointment->id,
                    'name' => $diagnose['diagnose_name'],
                    'type' => $diagnose['diagnose_type'],
                    'date' => $now,
                    'description' => $diagnose['description'] ?? null,
                ]);
                Log::info("Analytics created: {$analytics->id}");
            }

            $cloudinary = app(Cloudinary::class);
            foreach ($validated['analysiss'] as $analysisData) {
                $file = $analysisData['analysis_image'];

                $uploadedFile = $cloudinary->uploadApi()->upload(
                    $file->getRealPath(),
                    ['folder' => 'analysis_images']
                );

                $medicalRecord = MedicalRecords::create([
                    'appointment_id' => $appointment->id,
                    'name' => $analysisData['analysis_name'],
                    'date' => $now,
                    'image_path' => $uploadedFile['secure_url'],
                    'description' => $analysisData['description'] ?? null,
                ]);
                Log::info("Medical record created: {$medicalRecord->id}");
            }

            foreach ($validated['medicines'] as $medicineData) {
                $medicineSchedule = MedicineSchedules::create([
                    'appointment_id' => $appointment->id,
                    'medicine_id' => $medicineData['medicine_id'],
                    'rest_time' => $medicineData['rest_time'],
                    'quantity' => $medicineData['quantity'],
                    'description' => $medicineData['description'] ?? null,
                ]);
                Log::info("Medicine schedule created: {$medicineSchedule->id}");
            }

            // تحديث حالة الموعد إلى finished
            $appointment->finished = 1;
            $appointment->save();
            Log::info("Appointment marked as finished: {$appointment->id}");

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Transaction failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Doctor data saved successfully'
        ], 200);
    }

    // إضافة فحص باستخدام appointment_id
    public function addExamin_by_appointment_id(Request $request) {
        $user = Auth::user();
        abort_unless($user, 404);

        $validated = $request->validate([
            'apointment_id' => 'required|exists:appointments,id',
            'diagnoses' => [
                'nullable', 'array',
                function ($attributes, $value, $fail) {
                    foreach ($value as $diagnose) {
                        if (!isset($diagnose['diagnose_name']) || !isset($diagnose['diagnose_type'])) {
                            $fail('Each diagnose must contain: diagnose name, diagnose type.');
                        }
                    }
                }
            ],
            'diagnoses.*.diagnose_name' => 'required|string|max:255',
            'diagnoses.*.diagnose_type' => 'required|in:temporary,non-temporary',
            'diagnoses.*.description' => 'nullable|string',
            'analysiss' => [
                'nullable', 'array',
                function ($attributes, $value, $fail) {
                    foreach ($value as $analysis) {
                        if (!isset($analysis['analysis_name']) || !isset($analysis['analysis_image'])) {
                            $fail('Each analysis must contain: analysis name and image.');
                        }
                    }
                }
            ],
            'analysiss.*.analysis_name' => 'required|string|max:255',
            'analysiss.*.analysis_image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'analysiss.*.description' => 'nullable|string',
            'medicines' => [
                'nullable', 'array',
                function ($attributes, $value, $fail) {
                    foreach ($value as $medicine) {
                        if (!isset($medicine['medicine_id']) || !isset($medicine['rest_time']) || !isset($medicine['quantity'])) {
                            $fail('Each medicine must contain: medicine id, rest time, quantity.');
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
        $appointment = Appointment::findOrFail($request->apointment_id);

        try {
            DB::beginTransaction();

            foreach ($validated['diagnoses'] as $diagnose) {
                $analytics = Analytics::create([
                    'appointment_id' => $appointment->id,
                    'name' => $diagnose['diagnose_name'],
                    'type' => $diagnose['diagnose_type'],
                    'date' => $now,
                    'description' => $diagnose['description'] ?? null,
                ]);
                Log::info("Analytics created: {$analytics->id}");
            }

            $cloudinary = app(Cloudinary::class);
            foreach ($validated['analysiss'] as $analysisData) {
                $file = $analysisData['analysis_image'];

                $uploadedFile = $cloudinary->uploadApi()->upload(
                    $file->getRealPath(),
                    ['folder' => 'analysis_images']
                );

                $medicalRecord = MedicalRecords::create([
                    'appointment_id' => $appointment->id,
                    'name' => $analysisData['analysis_name'],
                    'date' => $now,
                    'image_path' => $uploadedFile['secure_url'],
                    'description' => $analysisData['description'] ?? null,
                ]);
                Log::info("Medical record created: {$medicalRecord->id}");
            }

            foreach ($validated['medicines'] as $medicineData) {
                $medicineSchedule = MedicineSchedules::create([
                    'appointment_id' => $appointment->id,
                    'medicine_id' => $medicineData['medicine_id'],
                    'rest_time' => $medicineData['rest_time'],
                    'quantity' => $medicineData['quantity'],
                    'description' => $medicineData['description'] ?? null,
                ]);
                Log::info("Medicine schedule created: {$medicineSchedule->id}");
            }

            $appointment->finished = 1;
            $appointment->save();
            Log::info("Appointment marked as finished: {$appointment->id}");

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Transaction failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Doctor data saved successfully'
        ], 200);
    }
}
