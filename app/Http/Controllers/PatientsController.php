<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Models\MedicineSchedules;
use App\Models\Patients;
use App\Models\User;
use App\Services\FirebaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stichoza\GoogleTranslate\GoogleTranslate;

class PatientsController extends Controller
{
    public function getAuthenticatedPatientData()
    {
        $lan = request()->header('lan', 'en');
        $translator = new GoogleTranslate($lan);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => $translator->translate('User not authenticated.')
            ], 401);
        }

        $patient = Patients::where('user_id', $user->id)->first();

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => $translator->translate('Patient data not found.')
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => $translator->translate('User data retrieved successfully.'),
            'data' => [
                'id' => $user->id,
                'patient_id' => $patient->id,
                'balance' => $user->balance,
                'first_name' => $user->first_name,
                'middle_name' => $user->middle_name,
                'last_name' => $user->last_name,
                'number' => $user->number,
                'mother_name' => $user->mother_name,
                'birth_day' => $user->birth_day,
                'national_number' => $user->national_number,
                'gender' => $lan === 'ar' ? ($user->gender === 'male' ? 'ذكر' : 'أنثى') : $user->gender,
                'daily_doses_number' => $patient->daily_doses_number,
                'taken_doses' => $patient->taken_doses,
            ]
        ]);
    }

    public function getPatientById($id)
    {
        $lan = request()->header('lan', 'en');
        $translator = new GoogleTranslate($lan);

        $patient = Patients::find($id);
        $user = User::find($id);

        if (!$user || !$patient) {
            return response()->json([
                'success' => false,
                'message' => $translator->translate('User not found.'),
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => $translator->translate('User data retrieved successfully.'),
            'data' => [
                'id' => $user->id,
                'balance' => $user->balance,
                'patient_id' => $patient->id,

                'first_name' => $user->first_name,
                'middle_name' => $user->middle_name,
                'last_name' => $user->last_name,
                'number' => $user->number,
                'mother_name' => $user->mother_name,
                'birth_day' => $user->birth_day,
                'national_number' => $user->national_number,
                'gender' => $lan === 'ar' ? ($user->gender === 'male' ? 'ذكر' : 'أنثى') : $user->gender,
                'daily_doses_number' => $patient->daily_doses_number,
                'taken_doses' => $patient->taken_doses,
            ]
        ]);
    }

    public function create() {}

    public function check_fill(PatientRequest $request)
    {
        $lan = request()->header('lan', 'en');
        $translator = new GoogleTranslate($lan);

        $user = Auth::user();
        $user = User::where('id', $user->id)->first();
        $patient = Patients::where('user_id', $user->id)->first();

        if ($patient) {
            return response()->json([
                'success' => true,
                'message' => $translator->translate('The data already exists.')
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => $translator->translate('You have to fill in the data.')
            ], 200);
        }
    }

    public function store(Request $request)
    {
        $lan = request()->header('lan', 'en');
        $translator = new GoogleTranslate($lan);

        $user = Auth::user();
        $user = User::where('id', $user->id)->first();

        $request->merge(['user_id' => $user->id]);

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

            if (!Patients::where('user_id', $user->id)->exists()) {
                Patients::create(['user_id' => $user->id]);
            }

            if (!$user->hasRole('patient')) {
                $user->assignRole('patient');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $translator->translate('Patient created successfully.')
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $translator->translate('Something went wrong. Please try again.'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        $lan = request()->header('lan', 'en');
        $translator = new GoogleTranslate($lan);

        $patient = Patients::where('id', $request->id)->first();

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => $translator->translate('Patient not found.')
            ], 404);
        }

        $patient->delete();

        return response()->json([
            'success' => true,
            'message' => $translator->translate('Patient deleted successfully.')
        ]);
    }

    public function report($id)
    {
        $lan = request()->header('lan', 'en');
        $translator = new GoogleTranslate($lan);

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => $translator->translate('User not found.'),
                'data' => [],
            ], 404);
        }

        $full_name = trim(
            ($user->first_name ?? 'unknown') . ' ' .
                ($user->middle_name ?? '') . ' ' .
                ($user->last_name ?? '')
        );

        $age = $user->birth_day
            ? Carbon::parse($user->birth_day)->age
            : 'unknown';

        $patient = Patients::where('user_id', $id)->first();

        $diagnoses = $patient && $patient->analytics
            ? $patient->analytics
            : collect(['unknown']);

        $diagnoses = $patient && $patient->analytics
            ? $patient->analytics
            : collect([]);

        $diagnostics = $diagnoses->map(function ($item) {
            return [
                'name' => $item->name ?? 'unknown',
                'type' => $item->description ?? 'unknown',
            ];
        })->values()->toArray();




        $analyzes = $patient && $patient->medicalRecord
            ? $patient->medicalRecord->map(function ($record) {
                return [
                    'name'       => $record->name,
                    'image_path' => $record->image_path,
                ];
            })->values()->toArray()
            : [];



        $medicines = $patient && $patient->medicineSchedule
            ? $patient->medicineSchedule()
            ->with('medicine')
            ->get()
            ->filter(fn($record) => $record->quantity > $record->number_of_taken_doses)
            ->map(function ($record) {
                return [
                    'medicine_name'         => $record->medicine->name,
                    'quantity'              => $record->quantity,
                    'number_of_taken_doses' => $record->number_of_taken_doses,
                    'rest_time'             => $record->rest_time,
                    'last_time_has_taken'   => $record->last_time_has_taken ?: "لم يأخذ أي جرعة بعد",
                ];
            })
            ->values()
            ->toArray()
            : [];




        $diagnosticsTranslated = array_map(function ($diag) use ($translator) {
            return [
                'name' => $translator->translate($diag['name']),
                'type' => $translator->translate($diag['type']),
            ];
        }, $diagnostics);
        $translatedMedicalRecord = array_map(function ($med_r) use ($translator) {
            return [
                'name' => $translator->translate($med_r['name']),
                'image_path' => $med_r['image_path'],
            ];
        }, $analyzes);
        $medicinesTranslated = array_map(function ($medicine) use ($translator) {
            return [
                'medicine_name'         => $translator->translate($medicine['medicine_name']),
                'quantity'              => $medicine['quantity'],
                'number_of_taken_doses' => $medicine['number_of_taken_doses'],
                'rest_time'             => $medicine['rest_time'],
                'last_time_has_taken'   => $medicine['last_time_has_taken'],
            ];
        }, $medicines);

        return response()->json([
            'success' => true,
            'message' => $translator->translate('User data retrieved successfully.'),
            'data' => [
                'full_name'        => $translator->translate($full_name) ?: 'unknown',
                'gender'            => $translator->translate($user->gender),
                'age'              => $age,
                'diagnostics'        => $diagnosticsTranslated,
                'analyzes'    => $translatedMedicalRecord,
                'medicines' => $medicinesTranslated,
            ]
        ]);
    }

    public function medicalInfo()
    {
        $lan = request()->header('lan', 'en');
        $translator = new GoogleTranslate($lan);


        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $patient = Patients::where('user_id', $user->id)->first();

        $medicines = $patient && $patient->medicineSchedule
            ? $patient->medicineSchedule()
            ->with('medicine') // جلب اسم الدواء
            ->get()
            ->filter(fn($record) => $record->quantity > $record->number_of_taken_doses)
            ->map(function ($record) {
                return [
                    'medicine_name'         => $record->medicine->name,
                    'medicine_id'           => $record->id,
                    'quantity'              => $record->quantity,
                    'number_of_taken_doses' => $record->number_of_taken_doses,
                    'rest_time'             => $record->rest_time,

                    'last_time_has_taken' => $record->last_time_has_taken
                        ? Carbon::parse($record->last_time_has_taken)->format('d/m/Y H:i')
                        : 'لم يأخذ أي جرعة بعد',
                ];
            })
            ->values()
            ->toArray()
            : [];


        $medicinesTranslated = array_map(function ($medicine) use ($translator) {
            return [
                'medicine_name'         => $translator->translate($medicine['medicine_name']),
                'quantity'              => $medicine['quantity'],
                'medicine_id'           => $medicine['medicine_id'],
                'number_of_taken_doses' => $medicine['number_of_taken_doses'],
                'rest_time'             => $medicine['rest_time'],
                'last_time_has_taken'   => $medicine['last_time_has_taken'],
            ];
        }, $medicines);

        return response()->json([
            'success' => true,
            'message' => $translator->translate('User data retrieved successfully.'),
            'data' => [

                'medicines' => $medicinesTranslated,
            ]
        ]);
    }

    public function confirmTaken(Request $request)
    {
        $lan = $request->header('lan', 'en');
        $translator = new GoogleTranslate($lan);

        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => $translator->translate('Unauthorized')
            ], 401);
        }

        $patient = Patients::where('user_id', $user->id)->first();
        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => $translator->translate('Patient not found.')
            ], 404);
        }

        $request->validate([
            'MedicineSchedules_id' => 'required|exists:medicine_schedules,id'
        ]);

        $medical = MedicineSchedules::find($request->MedicineSchedules_id);

        if ($medical->number_of_taken_doses < $medical->quantity) {
            $medical->number_of_taken_doses++;
            $medical->last_time_has_taken = now()->addHours(3);
            $medical->save();

            // ✅ إذا وصل لآخر جرعة ابعت إشعار للدكتور
            if ($medical->number_of_taken_doses == $medical->quantity) {
                $doctor = $medical->doctor; // لازم يكون عندك علاقة doctor بالـ MedicineSchedules
                if ($doctor && $doctor->user) {
                    $firebaseService = app(\App\Services\FirebaseService::class);
                    $tokens = $doctor->user->fcmTokens()->pluck('token');

                    foreach ($tokens as $token) {
                        try {
                            $firebaseService->sendNotification(
                                $token,
                                $translator->translate('انتهاء الدواء'),
                                $translator->translate('المريض ') . $patient->user->name .
                                    $translator->translate(' أنهى جميع جرعات دوائه. يرجى المتابعة معه.')
                            );
                        } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
                            \App\Models\FcmToken::where('token', $token)->delete();
                        } catch (\Exception $e) {
                            continue; //
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => $translator->translate('💚  Wishing you good health.'),
                'data' => [
                    'current_taken' => $medical->number_of_taken_doses,
                    'total_quantity' => $medical->quantity,
                    'time' => $medical->last_time_has_taken
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $translator->translate('Number of taken doses did not update.'),
                'data' => [
                    'current_taken' => $medical->number_of_taken_doses,
                    'total_quantity' => $medical->quantity,
                    'time' => $medical->last_time_has_taken,
                ]
            ]);
        }
    }
}
