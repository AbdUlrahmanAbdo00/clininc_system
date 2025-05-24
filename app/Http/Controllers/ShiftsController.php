<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftRequest;
use App\Models\Doctors;
use App\Models\Shift;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftsController extends Controller
{
    public function store(ShiftRequest $request){

        $validated = $request->validated();

         $shift = Shift::create($validated);
   
        if($shift)
    {
        return response()->json(['success' => 'Shift created successfully  .']);}
        else{
        return response()->json(['error' => 'Shift didnt create  .']);
        }
        
    }

    public function assignShiftToDoctor(Request $request)
{
    // $request->validate([
    //     'doctor_id'   => 'required|exists:doctors,id',
    //     'shift_type'  => 'required|exists:shifts,shift_type',
    //     'days'        => 'required|array',
    //     'days.*'      => 'in:Saturday,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday',
    // ]);

    // $doctorId  = $request->doctor_id;
    // $shiftType = $request->shift_type;
    // $days      = $request->days;

    
    // $shift = Shift::where('shift_type', $shiftType)->first();

    // if (!$shift) {
    //     return response()->json(['error' => 'Shift type not found.'], 404);
    // }


    // $doctor = Doctors::findOrFail($doctorId);
    // $doctor->shifts()->attach($shift->id, [
    //    'days' => json_encode($days)
    // ]);

    // return response()->json(['success' => 'Shift assigned to doctor successfully.']);
        $request->validate([
        'doctor_id'   => 'required|exists:doctors,id',
        'shift_type'  => 'required|exists:shifts,shift_type',
        'days'        => 'required|array',
        'days.*'      => 'in:Saturday,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday',
    ]);

    $doctorId  = $request->doctor_id;
    $shiftType = $request->shift_type;
    $days      = $request->days;

    $shift = Shift::where('shift_type', $shiftType)->first();

    if (!$shift) {
        return response()->json(['error' => 'Shift type not found.'], 404);
    }

    
    $existing = DB::table('doctor_shift')
        ->where('doctor_id', $doctorId)
        ->where('shift_id', $shift->id)
        ->get();

    foreach ($existing as $record) {
        $existingDays = json_decode($record->days, true);
        if (!empty(array_intersect($existingDays, $days))) {
            return response()->json(['error' => 'Doctor is already assigned to this shift on one or more of the selected days.'], 422);
        }
    }

    
    Doctors::find($doctorId)->shifts()->attach($shift->id, [
        'days' => json_encode($days)
    ]);

    return response()->json(['success' => 'Shift assigned to doctor successfully.']);
}

}
