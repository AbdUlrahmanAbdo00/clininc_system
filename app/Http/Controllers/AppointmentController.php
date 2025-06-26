<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctors;
use App\Models\Patients;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Services\AppointmentBookingService;

class AppointmentController extends Controller
{

    public function booking(Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',    
            'doctor_id' => 'required|integer|exists:doctors,id', 
        ]);
        $date = $request->date;
        $user = Auth::user();
        $user = User::findOrFail($user->id);
        $doctor = Doctors::findOrFail($request->doctor_id);
        $shifts = $doctor->shifts;



        $dayShifts = $this->groupShiftsByDays($shifts);

        $start = Carbon::parse($date);
        $end = $start->copy()->endOfMonth();

        $dateShiftMap = collect();

        $consultationDuration = $doctor->consultation_duration ?? 30;

        for ($current = $start->copy(); $current->lte($end); $current->addDay()) {
            $dayName = $current->format('l');
            $shiftsForDay = $dayShifts->get($dayName, collect());

            if ($shiftsForDay->isEmpty()) {

                //   $dateShiftMap->put($current->format('Y-m-d'), null);
                continue;
            }

            $availableSlot = false;


            foreach ($shiftsForDay as $shift) {
                $shiftStart = Carbon::parse($shift->start_time);
                $shiftEnd = Carbon::parse($shift->end_time);


                for ($slotStart = $shiftStart->copy(); $slotStart->lt($shiftEnd); $slotStart->addMinutes($consultationDuration)) {
                    $slotEnd = $slotStart->copy()->addMinutes($consultationDuration);
                    $breakStart = Carbon::parse($shift->start_break_time);
                    $breakEnd = Carbon::parse($shift->end_break_time);

                    if ($slotEnd->lte($breakStart) || $slotStart->gte($breakEnd)) {
                        $exists = \App\Models\Appointment::where('doctor_id', $doctor->id)
                            ->whereDate('date', $current->format('Y-m-d'))
                            ->where(function ($query) use ($slotStart, $slotEnd) {
                                $query->whereBetween('start_date', [$slotStart->format('H:i:s'), $slotEnd->format('H:i:s')])
                                    ->orWhereBetween('end_date', [$slotStart->format('H:i:s'), $slotEnd->format('H:i:s')]);
                            })
                            ->exists();
                    } else {
                        $exists = false;
                        continue;
                    }



                    if (!$exists) {

                        $availableSlot = true;
                        break 2;
                    }
                }
            }


            $dateShiftMap->put($current->format('Y-m-d'), $availableSlot);
        }
        $availableSlots = $dateShiftMap->map(function ($isAvailable, $date) {
            return [
                'date' => $date,
                'isAvailable' => $isAvailable,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'message' => 'This is the avaliable day.',
            'data' => [
                'available_day' => $availableSlots,
            ],
        ], 200);
    }
    public function deleteAppointment($id)
{
    $appointment = Appointment::find($id);

    if (!$appointment) {
        return response()->json([
            'success' => false,
            'message' => 'Appointment not found.',
        ], 404);
    }

    $appointment->delete();

    return response()->json([
        'success' => true,
        'message' => 'Appointment deleted successfully.',
    ]);
}

    public function getAvailableSlotsForDay(Request $request)
    {
            $request->validate([
            'date' => 'required|date_format:Y-m-d',    
            'doctor_id' => 'required|integer|exists:doctors,id', 
        ]);
        $date = Carbon::parse($request->date);
        $doctor = Doctors::findOrFail($request->doctor_id);
        $shifts = $doctor->shifts;

        $dayShifts = $this->groupShiftsByDays($shifts);

        $dayName = $date->format('l');
        $shiftsForDay = $dayShifts->get($dayName, collect());

        if ($shiftsForDay->isEmpty()) {
            return response()->json(['available_slots' => []]);
        }

        $consultationDuration = $doctor->consultation_duration ?? 30;
        $availableSlots = [];

        foreach ($shiftsForDay as $shift) {
            $shiftStart = Carbon::parse($date->toDateString() . ' ' . $shift->start_time);
            $shiftEnd = Carbon::parse($date->toDateString() . ' ' . $shift->end_time);

            $breakStart = isset($shift->start_break_time) ? Carbon::parse($date->toDateString() . ' ' . $shift->start_break_time) : null;
            $breakEnd = isset($shift->end_break_time) ? Carbon::parse($date->toDateString() . ' ' . $shift->end_break_time) : null;


            for ($slotStart = $shiftStart->copy(); $slotStart->lt($shiftEnd); $slotStart->addMinutes($consultationDuration)) {
                $slotEnd = $slotStart->copy()->addMinutes($consultationDuration);

                if ($breakStart && $breakEnd) {
                    $overlapsWithBreak = $slotStart->lt($breakEnd) && $slotEnd->gt($breakStart);
                    if ($overlapsWithBreak) {
                        continue;
                    }
                }

                $exists = \App\Models\Appointment::where('doctor_id', $doctor->id)
                    ->whereDate('date', $date->toDateString())
                    ->where(function ($query) use ($slotStart, $slotEnd) {
                        $query->where(function ($q) use ($slotStart, $slotEnd) {
                            $q->where('start_date', '<', $slotEnd->format('H:i:s'))
                                ->where('end_date', '>', $slotStart->format('H:i:s'));
                        });
                    })
                    ->exists();

                if (!$exists) {
                    $availableSlots[] = [
                        'available' => true,
                        'start' => $slotStart->format('H:i'),
                        'end' => $slotEnd->format('H:i'),
                    ];
                } else
                    $availableSlots[] = [
                        'available' => false,
                        'start' => $slotStart->format('H:i'),
                        'end' => $slotEnd->format('H:i'),
                    ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'This is the avaliable times.',
            'data' => [
                'available_slots' => $availableSlots
            ],

        ]);
    }
    public function groupShiftsByDays($shifts): Collection //function return collection the key is the day and the value the shifts like {'sunday'= ['morining',evning']}
    {
        $dayShifts = collect();

        foreach ($shifts as $shift) {
            $days = json_decode($shift->pivot->days, true);
            foreach ($days as $day) {
                $dayShifts->put($day, $dayShifts->get($day, collect())->push($shift));
            }
        }
        $dayShifts = $dayShifts->map(function ($shifts) {
            return $shifts->unique();
        });
        return $dayShifts;
    }


    public function book(Request $request, AppointmentBookingService $bookingService)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
        ]);
        $dateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->start_time);

        if ($dateTime->isPast()) {
            return response()->json([
                'error' => 'You cant afford to make a date in the past.',
            ], 422); // Unprocessable Entity
        }
        try {
            $appointment = $bookingService->bookAppointment($request->all());

            return response()->json([
                'message' => ' Your appointment has been booked successfully.',
                //   'appointment' => $appointment,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 409); // Conflict
        }
    }
public function showBookedappointmentForPatient(Request $request)
{
    $request->validate([
        'finished' => 'required|boolean'
    ]);
    
   
    $user = auth('sanctum')->user();
    
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 401);
    }
    

    $patient = Patients::where('user_id', $user->id)->first();
    
    if (!$patient) {
        return response()->json([
            'success' => false,
            'message' => 'Patient not found for this user.'
        ], 404);
    }
    
    $appointments = Appointment::where('patient_id', $patient->id)
                              ->where('finished', $request->finished)
                              ->get();
    
    if ($appointments->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No appointments found.'
        ], 200);
    }
    
    return response()->json([
        'success' => true,
        'data' => $appointments->map(function ($appointment) use ($user) {
            $doctor = Doctors::where('id', $appointment->doctor_id)->first();
            $doctorUser = User::where('id', $doctor->user_id)->first();
            
            return [
                'date' => $appointment->date,
                'appointment_id' => $appointment->id,
                'doctor_name' => $doctorUser->first_name,
                'patient_first_name' => $user->first_name,
            ];
        })
    ], 200);
}


public function showBookedappointmentForDoctor(Request $request)
{
    $request->validate([
        'finished' => 'required|boolean'
    ]);

    $user = auth('sanctum')->user();
    
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 401);
    }

    $doctor = Doctors::where('user_id', $user->id)->first();
    
    if (!$doctor) {
        return response()->json([
            'success' => false,
            'message' => 'Doctor not found for this user.'
        ], 404);
    }

    $appointments = Appointment::where('doctor_id', $doctor->id)
                    ->where('finished', $request->finished)
                    ->get();

    if ($appointments->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No appointments found.'
        ], 200);
    }

    return response()->json([
        'success' => true,
        'data' => $appointments->map(function ($appointment) use ($user) {
            $patient = Patients::where('id', $appointment->patient_id)->first();
            $patientUser = User::where('id', $patient->user_id)->first();
            
            return [
                'date' => $appointment->date,
                'appointment_id' => $appointment->id,
                'patient_name' => $patientUser->first_name ?? 'Unknown',
                'doctor_name' => $user->first_name,
            ];
        })
    ], 200);
}

}
