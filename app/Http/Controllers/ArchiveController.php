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

class ArchiveController extends Controller
{
    // This function shows the user who is in this case a patient his all upcoming appointments
    public function showUpcomingArchive_P() {
        $user = Auth::user();
        abort_unless($user, 404);
        $patient = Patients::where('user_id', $user->id)->first();
        $perPage = request()->input('pageSize', 5);

        $appointments = Appointment::where([
                ['patient_id', '=', $patient->id],
                ['finished', '=', 0]
            ])->paginate($perPage);

        $transformedItems = $appointments->getCollection()->map(function ($appointment) {
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
            'message' => $appointments->isEmpty() 
                ? 'No upcoming appointments found' 
                : 'Upcoming appointments loaded successfully',
            'data' => $appointments->items()
        ], 200);
    }

    // This function shows the user who is in this case a doctor his all upcoming appointments
    public function showUpcomingArchive_D() {
        $user = Auth::user();
        abort_unless($user, 404);
        $doctor = Doctors::where('user_id', $user->id)->first();
        $perPage = request()->input('pageSize', 5);

        $appointments = Appointment::where([
                ['doctor_id', '=', $doctor->id],
                ['finished', '=', 0]
            ])->paginate($perPage);

        $transformedItems = $appointments->getCollection()->map(function ($appointment) {
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
            'message' => $appointments->isEmpty() 
                ? 'No upcoming appointments found' 
                : 'Upcoming appointments loaded successfully',
            'data' => $appointments->items()
        ], 200);
    }

    // This function shows the user who is in this case a patient his all finished appointments
    public function showFinishedArchive_P() {
        $user = Auth::user();
        abort_unless($user, 404);
        $patient = Patients::where('user_id', $user->id)->first();
        $perPage = request()->input('pageSize', 5);

        $appointments = Appointment::where([
                ['patient_id', '=', $patient->id],
                ['finished', '=', 1]
            ])->paginate($perPage);

        $transformedItems = $appointments->getCollection()->map(function ($appointment) {
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
                            ->select('medicine_schedules.id', 
                            'medicines.name as medicine_name',
                            'medicine_schedules.quantity',
                            'medicine_schedules.number_of_taken_doses',
                            'medicine_schedules.rest_time',
                            'medicine_schedules.last_time_has_taken')
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
                'recipe' => [
                    'diagnostics' => $diagnostics,
                    'analyzes' => $analyzes,
                    'medicines' => $medicines
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
            'message' => $appointments->isEmpty() 
                ? 'No finished appointments found' 
                : 'Finished appointments loaded successfully',
            'data' => $appointments->items()
        ], 200);
    }

    // This function shows the user who is in this case a doctor his all upcoming appointments
    public function showFinishedArchive_D() {
        $user = Auth::user();
        abort_unless($user, 404);
        $doctor = Doctors::where('user_id', $user->id)->first();
        $perPage = request()->input('pageSize', 5);

        $appointments = Appointment::where([
                ['doctor_id', '=', $doctor->id],
                ['finished', '=', 1]
            ])->paginate($perPage);

        $transformedItems = $appointments->getCollection()->map(function ($appointment) {
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
                            ->select('medicine_schedules.id', 
                            'medicines.name as medicine_name',
                            'medicine_schedules.quantity',
                            'medicine_schedules.number_of_taken_doses',
                            'medicine_schedules.rest_time',
                            'medicine_schedules.last_time_has_taken')
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
                'recipe' => [
                    'diagnostics' => $diagnostics,
                    'analyzes' => $analyzes,
                    'medicines' => $medicines
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
            'message' => $appointments->isEmpty() 
                ? 'No finished appointments found' 
                : 'Finished appointments loaded successfully',
            'data' => $appointments->items()
        ], 200);
    }
}
