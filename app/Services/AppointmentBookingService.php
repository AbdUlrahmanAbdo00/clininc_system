<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AppointmentBookingService
{
   
    public function bookAppointment(array $data)
    {
        $doctorId = $data['doctor_id'];
        $date = $data['date']; 
        $startTime = $data['start_time'];
        $duration = $data['duration'] ?? 30;

        return DB::transaction(function () use ($doctorId, $date, $startTime, $duration) {
           
            $slotKey = $this->generateSlotKey($doctorId, $date, $startTime);

           
            DB::select("SELECT pg_advisory_xact_lock(?)", [$slotKey]);

            
            $exists = Appointment::where('doctor_id', $doctorId)
                ->whereDate('date', $date)
                ->where('start_date', $startTime)
                ->exists();

            if ($exists) {
                throw new \Exception("This appointment is already booked.");
            }

            return Appointment::create([
                'doctor_id' => $doctorId,
                'date' => $date,
                'start_date' => $startTime,
                'end_date' => Carbon::parse("$date $startTime")->addMinutes($duration)->format('H:i:s'),
                'patient_id' => auth('sanctum')->id(),
            ]);
        });
    }


    protected function generateSlotKey($doctorId, $date, $startTime)
    {
        $keyString = $doctorId . $date . $startTime;
        return (int) crc32($keyString); 
    }
}
