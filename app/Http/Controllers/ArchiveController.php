<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Doctors;
use App\Models\Patients;
use App\Models\User;

class ArchiveController extends Controller
{
    // This function shows the user who is in this case a patient his all upcoming appointments
    public function showUpcomingArchive_P() {
        $user = Auth::user();
        abort_unless($user, 404);
        $totalItems = 0;

        $patient = Patients::where('user_id', $user->id)->first();

        $appointments = Appointment::where([
                ['patient_id', '=', $patient->id],
                ['finished', '=', 0]
            ])
            ->get()
            ->map(function ($appointment) {
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

        $totalItems = $appointments->count();

        return response()->json([
            'currentPage' => 1,
            'pageSize' => 5,
            'totalPages' => 1,
            'totalItems' => $totalItems,
            'status' => true,
            'message' => $appointments->isEmpty() 
                ? 'No upcoming appointments found' 
                : 'Upcoming appointments loaded successfully',
            'data' => $appointments
        ], 200);
    }

    // This function shows the user who is in this case a doctor his all upcoming appointments
    public function showUpcomingArchive_D() {
        $user = Auth::user();
        abort_unless($user, 404);
        $totalItems = 0;

        $doctor = Doctors::where('user_id', $user->id)->first();

        $appointments = Appointment::where([
                ['doctor_id', '=', $doctor->id],
                ['finished', '=', 0]
            ])
            ->get()
            ->map(function ($appointment) {
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

        $totalItems = $appointments->count();

        return response()->json([
            'currentPage' => 1,
            'pageSize' => 5,
            'totalPages' => 1,
            'totalItems' => $totalItems,
            'status' => true,
            'message' => $appointments->isEmpty() 
                ? 'No upcoming appointments found' 
                : 'Upcoming appointments loaded successfully',
            'data' => $appointments
        ], 200);
    }
}
