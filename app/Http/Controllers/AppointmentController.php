<?php

namespace App\Http\Controllers;

use App\Models\Doctors;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
//     public function booking(Request $request)
//     {
//          $date = $request->date ;
//         $user = Auth::user();
//         //    dd($user);
//         $user = User::findOrFail($user->id);

//         //    dd($user);
//       $doctor = Doctors::findOrFail($request->doctor_id);
// $shifts = $doctor->shifts;


// $shiftDays = $shifts->groupBy('shift_type')->map(function ($shiftsGroup) {
//     return $shiftsGroup->flatMap(function ($shift) {
//         return collect(json_decode($shift->pivot->days, true));
//     })->unique()->values();
// });


// $dayShifts = collect();
// foreach ($shifts as $shift) {
//     $days = json_decode($shift->pivot->days, true);
//     foreach ($days as $day) {
//         $dayShifts->put($day, $dayShifts->get($day, collect())->push($shift->shift_type));
//     }
// }
// $dayShifts = $dayShifts->map(function ($shifts) {
//     return $shifts->unique()->values();
// });
// $start = Carbon::parse($date);
// $end = $start->copy()->endOfMonth();
// $dataShiftMap = collect();

// $dateShiftMap= $dayShifts;
// for($current= $start->copy();$current->lte($end);$current->addDay()){
//     $dayName = $current->format('l');
//     $shiftsForDay=$dayShifts->get($dayName,collect());
//             $dateShiftMap->put($current->format('Y-m-d'), $shiftsForDay);

// }





//     }
public function booking(Request $request)
{
    $date = $request->date;
    $user = Auth::user();
    $user = User::findOrFail($user->id);
    $doctor = Doctors::findOrFail($request->doctor_id);
    $shifts = $doctor->shifts;

    $shiftDays = $shifts->groupBy('shift_type')->map(function ($shiftsGroup) {
        return $shiftsGroup->flatMap(function ($shift) {
            return collect(json_decode($shift->pivot->days, true));
        })->unique()->values();
    });

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

    $start = Carbon::parse($date);
    $end = $start->copy()->endOfMonth();

    $dateShiftMap = collect();

    $consultationDuration = $doctor->consultation_duration ?? 30; 

    for ($current = $start->copy(); $current->lte($end); $current->addDay()) {
        $dayName = $current->format('l');
        $shiftsForDay = $dayShifts->get($dayName, collect());

        if ($shiftsForDay->isEmpty()) {
            
            $dateShiftMap->put($current->format('Y-m-d'), null);
            continue; 
        }

        $availableSlot = null;

        
        foreach ($shiftsForDay as $shift) {
            $shiftStart = Carbon::parse($shift->start_time);
            $shiftEnd = Carbon::parse($shift->end_time);

            
            for ($slotStart = $shiftStart->copy(); $slotStart->lt($shiftEnd); $slotStart->addMinutes($consultationDuration)) {
                $slotEnd = $slotStart->copy()->addMinutes($consultationDuration);


                $exists = \App\Models\Appointment::where('doctor_id', $doctor->id)
                    ->whereDate('date', $current->format('Y-m-d'))
                    ->where(function ($query) use ($slotStart, $slotEnd) {
                        $query->whereBetween('start_date', [$slotStart->format('H:i:s'), $slotEnd->format('H:i:s')])
                            ->orWhereBetween('end_date', [$slotStart->format('H:i:s'), $slotEnd->format('H:i:s')]);
                    })
                    ->exists();

                if (!$exists) {
                    
                    $availableSlot = [
                        'shift_type' => $shift->shift_type,
                        'start' => $slotStart->format('H:i'),
                        'end' => $slotEnd->format('H:i'),
                    ];
                    break 2; 
                }
            }
        }


        $dateShiftMap->put($current->format('Y-m-d'), $availableSlot);
    }

    return $dateShiftMap;
}

}
