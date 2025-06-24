<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Doctors;
use App\Models\User;

class archive extends Controller
{
    public function showUpcomingArchive_P() {
        $user = Auth::User();
        abort_unless($user, 404);

        $appointments = Appointment::with(
            'doctor.user',
            'user'
        )
            ->where('patient_id', $user->id)
            ->where('finished', false)
            ->get()
            ->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'doctorName' => 'Dr.' . $appointment->doctor->user->first_name . ' '
                . $appointment->doctor->user->last_name,
                'patientName' => $appointment->user->first_name . ' '
                . $appointment->user->last_name,
                'date' => $appointment->date,
                'recipe' => [
                    'diagnostics' => [],
                    'analyzes' => [],
                    'medicines' => []
                ],
                'status' => 'upcoming'
            ];
        });

        return response()->json([
            'currentPage' => 1,
            'pageSize' => 5,
            'totalPages' => 1,
            'totalItems' => 5,
            'status' => true,
            'message' => 'Upcoming appointments loaded successfully',
            'data' => $appointments
        ], 200);
    }
}
