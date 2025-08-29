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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stichoza\GoogleTranslate\GoogleTranslate;

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
                            ->where('cancled', NULL)
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
            return response()->json([
                'success' => true,
                'message' => 'لا توجد أوقات متاحة لهذا اليوم.',
                'data' => ['available_slots' => []]
            ]);
        }
    
        $consultationDuration = $doctor->consultation_duration ?? 30;
        $availableSlots = [];
        $now = Carbon::now();
    
        foreach ($shiftsForDay as $shift) {
            $shiftStart = Carbon::parse($date->toDateString() . ' ' . $shift->start_time);
            $shiftEnd = Carbon::parse($date->toDateString() . ' ' . $shift->end_time);
    
            $breakStart = isset($shift->start_break_time) ? Carbon::parse($date->toDateString() . ' ' . $shift->start_break_time) : null;
            $breakEnd = isset($shift->end_break_time) ? Carbon::parse($date->toDateString() . ' ' . $shift->end_break_time) : null;
    
            for ($slotStart = $shiftStart->copy(); $slotStart->lt($shiftEnd); $slotStart->addMinutes($consultationDuration)) {
               
                // dd( $slotStart);
                $slotEnd = $slotStart->copy()->addMinutes($consultationDuration);
                $now = Carbon::now('Asia/Beirut'); // مثال: 10:15:30
;
                if ($date->isToday() && $slotStart < $now) {

                    continue;
                }
    
             
                if ($breakStart && $breakEnd) {
                    $overlapsWithBreak = $slotStart->lt($breakEnd) && $slotEnd->gt($breakStart);
                    if ($overlapsWithBreak) {
                        continue;
                    }
                }
    
                $exists = \App\Models\Appointment::where('doctor_id', $doctor->id)
                    ->whereNull('cancled')
                    ->whereDate('date', $date->toDateString())
                    ->where(function ($query) use ($slotStart, $slotEnd) {
                        $query->where(function ($q) use ($slotStart, $slotEnd) {
                            $q->where('start_date', '<', $slotEnd->format('H:i:s'))
                              ->where('end_date', '>', $slotStart->format('H:i:s'));
                        });
                    })
                    ->exists();
    
                $availableSlots[] = [
                    'available' => !$exists,
                    'start' => $slotStart->format('H:i'),
                    'end' => $slotEnd->format('H:i'),
                ];
            }
        }
    
        return response()->json([
            'success' => true,
            'message' => 'هذه هي الأوقات المتاحة.',
            'data' => ['available_slots' => $availableSlots]
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
        $lan = $request->header('lan', 'en');
        $translator = new GoogleTranslate($lan);
    
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
        ]);
        $dateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->start_time);

        if ($dateTime->isPast()) {
            return response()->json([
                'error' => $translator->translate('You cant afford to make a date in the past.'),
            ], 422); // Unprocessable Entity
        }
        try {
            $appointment = $bookingService->bookAppointment($request->all());

            return response()->json([
                'success' => true,
                'message' => $translator->translate(' Your appointment has been booked successfully.'),
                'data' => [
                 'id'=>$appointment->id
                ]                
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

        $appointments = Appointment::where('cancled', NULL)
            ->where('patient_id', $patient->id)
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
                    'start_time' => \Carbon\Carbon::parse($appointment->start_date)->format('h:i A'),
                    'appointment_id' => $appointment->id,
                                        'paid'=>$appointment->is_paid,

                    'doctor_name' => $doctorUser->first_name . ' ' . $doctorUser->last_name,
                    'patient_name' => $user->first_name . ' ' . $user->last_name,
                    'specialization' => $doctor->specialization->name

                ];
            })
        ], 200);
    }

    public function showBookedappointmentForSecretary(Request $request)
    {
        $request->validate([
            'finished' => 'required|boolean',
            'date' => 'required|date|after_or_equal:today'
        ]);







        $appointments = Appointment::where('cancled', NULL)
            ->where('finished', $request->finished)
            ->where('date', $request->date)
            ->get();

        if ($appointments->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No appointments found.'
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data' => $appointments->map(function ($appointment) {
                $doctor = Doctors::where('id', $appointment->doctor_id)->first();
                $doctorUser = User::where('id', $doctor->user_id)->first();
                $patient = Patients::where('id', $appointment->patient_id)->first();
                $user = User::where('id', $patient->user_id)->first();
                return [
                    'date' => $appointment->date,
                    'paid' => $appointment->is_paid,

                    'start_time' => \Carbon\Carbon::parse($appointment->start_date)->format('h:i A'),
                    'appointment_id' => $appointment->id,
                    'doctor_name' => $doctorUser->first_name . ' ' . $doctorUser->last_name,
                    'patient_name' => $user->first_name . ' ' . $user->last_name,
                    'phone'        => $user->number,
                    'specialization' => $doctor->specialization->name

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
                $doctor = Doctors::where('id', $appointment->doctor_id)->first();


                return [
                    'date' => $appointment->date,
                    'start_time' => \Carbon\Carbon::parse($appointment->start_time)->format('h:i A'),
                    'paid' => $appointment->is_paid,

                    'appointment_id' => $appointment->id,
                    'patient_name' => $patientUser->first_name . ' ' . $patientUser->last_name ?? 'Unknown',
                    'doctor_name' =>  $fullName = $user->first_name . ' ' . $user->last_name,
                    'specialization' => $doctor->specialization->name
                ];
            })
        ], 200);
    }

    public function cancel(Request $request)
{
    $request->validate([
        'Appointment_id' => 'required|exists:appointments,id'
    ]);

    $lan = request()->header('lan', 'en');
    $translator = new GoogleTranslate($lan);

    $appointment = Appointment::findOrFail($request->Appointment_id);

    if ($appointment->cancled !== null) {
        return response()->json([
            'success' => true,
            'message' => $translator->translate('الموعد ملغى مسبقا')
        ]);
    }

    $user = auth('sanctum')->user();
    $user = User::find($user->id);
    $patient = Patients::where('user_id', $user->id)->first();
    $doctor = Doctors::where('user_id', $user->id)->first();

    $firebaseService = app(\App\Services\FirebaseService::class);

    $updateCancel = function ($entity, $role) use ($appointment, $firebaseService, $translator) {
        $now = Carbon::now();

        if ($appointment->is_paid && ($role === 'patient' || $role === 'secretary')) {
            $patientUser = $appointment->patient->user;
            $doctor = $appointment->doctor;

            $patientUser->balance += $doctor->price;
            $patientUser->save();

            $doctor->user->balance -= $doctor->price;
            $doctor->save();

            $appointment->is_paid = false;
            $appointment->save();
        }

        if ($role !== 'secretary') {
            if ($entity->last_canceled_at) {
                $last = Carbon::parse($entity->last_canceled_at);
                if ($last->month != $now->month || $last->year != $now->year) {
                    $entity->cancel_count = 0;
                }
            }

            if ($entity->cancel_count >= 10) {
                return response()->json([
                    'success' => false,
                    'message' => $translator->translate('لقد وصلت إلى الحد الأقصى للإلغاءات هذا الشهر (10 مواعيد).')
                ]);
            }

            $entity->increment('cancel_count');
            $entity->last_canceled_at = $now;
        }

        if ($role === 'doctor') {
            $appointment->cancled = 'cancled_by_doctor';
        } elseif ($role === 'patient') {
            $appointment->cancled = 'cancled_by_patient';
        } else {
            $appointment->cancled = 'cancled_by_secretary';
        }

        $entity->save();
        $appointment->save();

        $tokens = ($role === 'doctor' || $role === 'secretary')
            ? $appointment->patient->user->fcmTokens()->pluck('token')
            : $appointment->doctor->user->fcmTokens()->pluck('token');

        foreach ($tokens as $token) {
            try {
                $firebaseService->sendNotification(
                    $token,
                    $translator->translate('إلغاء موعد'),
                    $translator->translate('تم إلغاء الموعد من طرف ')
                        . $translator->translate($role === 'doctor' ? 'الطبيب' : ($role === 'patient' ? 'المريض' : 'السكرتيرة'))
                        . $translator->translate(' بتاريخ ' . $appointment->date)
                        . $translator->translate(' الساعة ' . $appointment->start_date)
                );
            } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
                \App\Models\FcmToken::where('token', $token)->delete();
            } catch (\Exception $e) {
                Log::error("فشل إرسال الإشعار للتوكن {$token}: " . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => $translator->translate('تم إلغاء الموعد بنجاح. إذا كان الموعد مدفوعاً، تم إعادة المبلغ للمريض.')
        ]);
    };

    if ($doctor && $appointment->doctor_id == $doctor->id) {
        return $updateCancel($doctor, 'doctor');
    } elseif ($patient && $appointment->patient_id == $patient->id) {
        return $updateCancel($patient, 'patient');
    } elseif ($user && $user->hasRole('secretary')) {
        return $updateCancel($user, 'secretary');
    }

    return response()->json([
        'success' => false,
        'message' => $translator->translate('انت لا تملك صلاحية الغاء هذا الموعد    .')
    ]);
}


    public function doctorsWorkingToday()
    {
        $today = Carbon::now()->format('l');

        $doctors = Doctors::whereHas('shifts', function ($query) use ($today) {
            $query->whereJsonContains('doctor_shift.days', $today);
        })->get()
            ->map(function ($doctor) {
                $shift = $doctor->shifts->first();
                $specialization = $doctor->specialization->first();

                return [
                    'id' => $doctor->id,
                    'name' => $doctor->user->first_name . ' ' . $doctor->user->last_name,
                    'bio' => $doctor->bio,
                    'specialization' => $specialization->name,
                    'specialization_img' => $specialization->path,
                    'imageUrl' => $doctor->imageUrl,
                    'consultation_duration' => $doctor->consultation_duration,
                    'start_time' => $shift?->start_time,
                    'end_time'   => $shift?->end_time,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Doctors working today.',
            'data' => $doctors,
        ], 200);
    }

    // public function payAppointment($appointmentId)
    // {
    //     $appointment = Appointment::findOrFail($appointmentId);


    //     $u = auth('sanctum')->user();
    //     $patientUser = User::where('id', $u->id)->first();
    //     $doctor  = $appointment->doctor;
    //     $price   = $doctor->price;


    //     if ($appointment->is_paid) {
    //         return response()->json(['message' => 'الموعد مدفوع مسبقاً'], 400);
    //     }


    //     if ($patientUser->balance < $price) {
    //         return response()->json(['message' => 'رصيدك غير كافي'], 400);
    //     }
    //     return DB::transaction(function () use ($patientUser, $doctor, $appointment, $price) {

    //         $patientUser->balance -= $price;
    //         $patientUser->save();

    //         $doctor->user->balance += $price;
    //         $doctor->user->save();

    //         $appointment->is_paid = true;
    //         $appointment->save();

    //         return response()->json(['message' => 'تم الدفع بنجاح']);
    //     });
    // }
    public function payAppointment(Request $request, $appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        $lan = $request->header('lan', 'en');
        $translator = new GoogleTranslate($lan);
    

        $u = auth('sanctum')->user();
        $patientUser = User::find($u->id);
        $doctor = $appointment->doctor;
        $price = $doctor->price;

        if ($appointment->is_paid) {
            return response()->json(['message' => 'الموعد مدفوع مسبقاً'], 400);
        }

        return DB::transaction(function () use ($patientUser, $doctor, $appointment, $price,$translator) {
            
            if ($patientUser->hasRole('secretary')) {
                $paymentMethod = 'cash';
            } else {
                $paymentMethod = 'balance';
                
                if ($patientUser->balance < $price) {
                    return response()->json(['message' => 'رصيد المريض غير كافي'], 400);
                }
                
                $patientUser->balance -= $price;
                $patientUser->save();
            }

            $doctor->user->balance += $price;
            $doctor->user->save();

            $appointment->is_paid = true;
            // $appointment->payment_method = $paymentMethod;
            $appointment->save();

            $message = $paymentMethod === 'cash' 
                ? 'تم الدفع نقداً بنجاح' 
                : 'تم الدفع من رصيد المريض بنجاح';

            return response()->json([
                'success' => true,
                'message' => $translator->translate($message),
                
            ]);
        });
    }

}
