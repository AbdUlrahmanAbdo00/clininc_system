<?php

namespace App\Http\Controllers;

use App\Models\Analytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Doctors;
use App\Models\MedicalRecords;
use App\Models\MedicineSchedules;
use App\Models\Patients;
use App\Models\User;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ArchiveController extends Controller
{
    // دالة مساعدة للترجمة
    private function translateMessage(Request $request, string $message)
    {
        $lan = $request->header('lan', 'en');
        $translator = new GoogleTranslate($lan);
        return $translator->translate($message);
    }

    public function showUpcomingArchive_P(Request $request)
    {
        $lang = $request->header('lan', 'en');
        $translator = new GoogleTranslate($lang);

        $user = Auth::user();
        abort_unless($user, 404);
        $patient = Patients::where('user_id', $user->id)->first();

        $perPage = $request->input('pageSize', 5);

        $appointments = Appointment::where([
            ['patient_id', '=', $patient->id],
            ['finished', '=', 0],
            ['cancled', '=', NULL]
        ])
        ->orderBy('date', 'asc')
        ->orderBy('start_date', 'asc')
            ->paginate($perPage);

        $transformedItems = $appointments->getCollection()->map(function ($appointment) use ($translator) {
            $doctor = Doctors::where('id', $appointment->doctor_id)->first();
            $doctorUser = User::where('id', $doctor->user_id)->first();
            $patient = Patients::where('id', $appointment->patient_id)->first();
            $patientUser = User::where('id', $patient->user_id)->first();

            return [
                'id' => $appointment->id,
                'doctorName' => $doctorUser
                    ? 'Dr. ' . $doctorUser->first_name . ' ' . $doctorUser->last_name
                    : 'Doctor Not Found',
                'patientName' => $patientUser
                    ? $patientUser->first_name . ' ' . $patientUser->last_name
                    : 'Patient Not Found',
                'paid' => $appointment->is_paid,
                'date' => $appointment->date,
                'start_time' => $appointment->start_date,
                'recipe' => [
                    'diagnostics' => [],
                    'analyzes' => [],
                    'medicines' => []
                ],
                'status' => 'upcoming'
            ];
        });

        $appointments->setCollection($transformedItems);

        return response()->json([
            'currentPage' => $appointments->currentPage(),
            'pageSize' => $appointments->perPage(),
            'totalPages' => $appointments->lastPage(),
            'totalItems' => $appointments->total(),
            'status' => true,
            'message' => $this->translateMessage($request, $appointments->isEmpty()
                ? 'No upcoming appointments found'
                : 'Upcoming appointments loaded successfully'),
            'data' => $appointments->items()
        ], 200);
    }

    public function showUpcomingArchive_D(Request $request)
    {
        $lang = $request->header('lan', 'en');
        $translator = new GoogleTranslate($lang);

        $user = Auth::user();
        abort_unless($user, 404);
        $doctor = Doctors::where('user_id', $user->id)->first();

        $perPage = $request->input('pageSize', 5);

        $appointments = Appointment::where([
            ['doctor_id', '=', $doctor->id],
            ['finished', '=', 0],
            ['cancled', '=', NULL]
        ])
        ->orderBy('date', 'asc')
        ->orderBy('start_date', 'asc')
        ->paginate($perPage);

        $transformedItems = $appointments->getCollection()->map(function ($appointment) use ($translator) {
            $doctor = Doctors::where('id', $appointment->doctor_id)->first();
            $doctorUser = User::where('id', $doctor->user_id)->first();
            $patient = Patients::where('id', $appointment->patient_id)->first();
            $patientUser = User::where('id', $patient->user_id)->first();

            return [
                'id' => $appointment->id,
                'doctorName' => $doctorUser
                    ? 'Dr. ' . $doctorUser->first_name . ' ' . $doctorUser->last_name
                    : 'Doctor Not Found',
                'paid' => $appointment->is_paid,

                'patientName' => $patientUser
                    ? $patientUser->first_name . ' ' . $patientUser->last_name
                    : 'Patient Not Found',
                'date' => $appointment->date,
                'recipe' => [
                    'diagnostics' => [],
                    'analyzes' => [],
                    'medicines' => []
                ],
                'status' => 'upcoming'
            ];
        });

        $appointments->setCollection($transformedItems);

        return response()->json([
            'currentPage' => $appointments->currentPage(),
            'pageSize' => $appointments->perPage(),
            'totalPages' => $appointments->lastPage(),
            'totalItems' => $appointments->total(),
            'status' => true,
            'message' => $this->translateMessage($request, $appointments->isEmpty()
                ? 'No upcoming appointments found'
                : 'Upcoming appointments loaded successfully'),
            'data' => $appointments->items()
        ], 200);
    }

    public function showFinishedArchive_P(Request $request)
    {
        $lang = $request->header('lan', 'en');
        $translator = new GoogleTranslate($lang);

        $user = Auth::user();
        abort_unless($user, 404);
        $patient = Patients::where('user_id', $user->id)->first();

        $perPage = $request->input('pageSize', 5);

        $appointments = Appointment::where([
            ['patient_id', '=', $patient->id],
            ['finished', '=', 1],
            ['cancled', '=', NULL]
        ])
        ->orderBy('date', 'asc')
        ->orderBy('start_date', 'asc')
        ->paginate($perPage);

        $transformedItems = $appointments->getCollection()->map(function ($appointment) use ($translator) {
            $doctor = Doctors::where('id', $appointment->doctor_id)->first();
            $doctorUser = User::where('id', $doctor->user_id)->first();
            $patient = Patients::where('id', $appointment->patient_id)->first();
            $patientUser = User::where('id', $patient->user_id)->first();

            $diagnostics = Analytics::where('appointment_id', $appointment->id)
                ->select('id', 'name', 'type')
                ->get();

            $analyzes = MedicalRecords::where('appointment_id', $appointment->id)
                ->select('id', 'name', 'image_path')
                ->get();

            $medicines = MedicineSchedules::where('appointment_id', $appointment->id)
                ->join('medicines', 'medicine_schedules.medicine_id', '=', 'medicines.id')
                ->select(
                    'medicine_schedules.id',
                    'medicines.name as medicine_name',
                    'medicine_schedules.quantity',
                    'medicine_schedules.number_of_taken_doses',
                    'medicine_schedules.rest_time',
                    'medicine_schedules.last_time_has_taken'
                )
                ->get();

            return [
                'id' => $appointment->id,
                'doctorName' => $doctorUser
                    ? 'Dr. ' . $doctorUser->first_name . ' ' . $doctorUser->last_name
                    : 'Doctor Not Found',
                'patientName' => $patientUser
                    ? $patientUser->first_name . ' ' . $patientUser->last_name
                    : 'Patient Not Found',
                'date' => $appointment->date,
                'paid' => $appointment->is_paid,

                'recipe' => [
                    'diagnostics' => $diagnostics,
                    'analyzes' => $analyzes,
                    'medicines' => $medicines
                ],
                'status' => 'finished'
            ];
        });

        $appointments->setCollection($transformedItems);

        return response()->json([
            'currentPage' => $appointments->currentPage(),
            'pageSize' => $appointments->perPage(),
            'totalPages' => $appointments->lastPage(),
            'totalItems' => $appointments->total(),
            'status' => true,
            'message' => $this->translateMessage($request, $appointments->isEmpty()
                ? 'No finished appointments found'
                : 'Finished appointments loaded successfully'),
            'data' => $appointments->items()
        ], 200);
    }

    public function showFinishedArchive_D(Request $request)
    {
        $lang = $request->header('lan', 'en');
        $translator = new GoogleTranslate($lang);

        $user = Auth::user();
        abort_unless($user, 404);
        $doctor = Doctors::where('user_id', $user->id)->first();

        $perPage = $request->input('pageSize', 5);

        $appointments = Appointment::where([
            ['doctor_id', '=', $doctor->id],
            ['finished', '=', 1],
            ['cancled', '=', NULL]
        ])
        ->orderBy('date', 'asc')
        ->orderBy('start_date', 'asc')
        ->paginate($perPage);

        $transformedItems = $appointments->getCollection()->map(function ($appointment) use ($translator) {
            $doctor = Doctors::where('id', $appointment->doctor_id)->first();
            $doctorUser = User::where('id', $doctor->user_id)->first();
            $patient = Patients::where('id', $appointment->patient_id)->first();
            $patientUser = User::where('id', $patient->user_id)->first();

            $diagnostics = Analytics::where('appointment_id', $appointment->id)
                ->select('id', 'name', 'type')
                ->get();

            $analyzes = MedicalRecords::where('appointment_id', $appointment->id)
                ->select('id', 'name', 'image_path')
                ->get();

            $medicines = MedicineSchedules::where('appointment_id', $appointment->id)
                ->join('medicines', 'medicine_schedules.medicine_id', '=', 'medicines.id')
                ->select(
                    'medicine_schedules.id',
                    'medicines.name as medicine_name',
                    'medicine_schedules.quantity',
                    'medicine_schedules.number_of_taken_doses',
                    'medicine_schedules.rest_time',
                    'medicine_schedules.last_time_has_taken'
                )
                ->get();

            return [
                'id' => $appointment->id,
                'paid' => $appointment->is_paid,

                'doctorName' => $doctorUser
                    ? 'Dr. ' .$doctorUser->first_name . ' ' . $doctorUser->last_name
                    : 'Doctor Not Found',
                'patientName' => $patientUser
                    ? $patientUser->first_name . ' ' . $patientUser->last_name
                    : 'Patient Not Found',
                'date' => $appointment->date,
                'recipe' => [
                    'diagnostics' => $diagnostics,
                    'analyzes' => $analyzes,
                    'medicines' => $medicines
                ],
                'status' => 'finished'
            ];
        });

        $appointments->setCollection($transformedItems);

        return response()->json([
            'currentPage' => $appointments->currentPage(),
            'pageSize' => $appointments->perPage(),
            'totalPages' => $appointments->lastPage(),
            'totalItems' => $appointments->total(),
            'status' => true,
            'message' => $this->translateMessage($request, $appointments->isEmpty()
                ? 'No finished appointments found'
                : 'Finished appointments loaded successfully'),
            'data' => $appointments->items()
        ], 200);
    }
}
