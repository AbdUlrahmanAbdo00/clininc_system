<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftRequest;
use Illuminate\Http\Request;
use App\Models\Doctors;
use App\Models\Specialization;
use App\Models\Shift;
use App\Models\Patients;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Cloudinary\Cloudinary;
use Stichoza\GoogleTranslate\GoogleTranslate;

class DashboardController extends Controller
{
    /**
     * عرض الصفحة الرئيسية للداشبورد
     */
    public function index()
    {
        // إحصائيات سريعة
        $stats = [
            'doctors' => Doctors::count(),
            'specializations' => Specialization::count(),
            'shifts' => Shift::count(),
            'patients' => Patients::count(),
            'appointments' => Appointment::count(),
        ];

        // إحصائيات المواعيد الحقيقية
        $appointmentStats = [
            'total' => Appointment::count(),
            'today' => Appointment::whereDate('date', today())->count(),
            'this_month' => Appointment::whereMonth('date', now()->month)->count(),
            'finished' => Appointment::where('finished', true)->count(),
            'cancelled' => Appointment::where('cancled', true)->count(),
        ];

        // إحصائيات المرضى الحقيقية
        $patientStats = [
            'total' => Patients::count(),
            'new_this_month' => Patients::whereMonth('created_at', now()->month)->count(),
            'active' => Patients::whereHas('appointments', function($query) {
                $query->where('date', '>=', now()->subMonths(3));
            })->count(),
        ];

        // إحصائيات الأطباء الحقيقية
        $doctorStats = [
            'total' => Doctors::count(),
            'with_appointments_today' => Doctors::whereHas('appointments', function($query) {
                $query->whereDate('date', today());
            })->count(),
            'by_specialization' => Doctors::with('specialization')
                ->get()
                ->groupBy('specialization.name')
                ->map->count(),
            'top_doctors' => Doctors::with(['user', 'specialization'])
                ->withCount(['appointments' => function($query) {
                    $query->whereMonth('date', now()->month);
                }])
                ->orderBy('appointments_count', 'desc')
                ->limit(5)
                ->get()
                ->map(function($doctor) {
                    return [
                        'name' => $doctor->user->first_name . ' ' . $doctor->user->last_name,
                        'specialization' => $doctor->specialization->name ?? 'غير محدد',
                        'appointments_count' => $doctor->appointments_count
                    ];
                })
        ];

        // إحصائيات الاختصاصات
        $specializationStats = [
            'top_specializations' => Specialization::withCount('doctors')
                ->orderBy('doctors_count', 'desc')
                ->limit(5)
                ->get()
                ->map(function($specialization) {
                    return [
                        'name' => $specialization->name,
                        'doctors_count' => $specialization->doctors_count
                    ];
                })
        ];

        return view('dashboard.index', compact(
            'stats', 
            'appointmentStats', 
            'patientStats', 
            'doctorStats',
            'specializationStats'
        ));
    }

    /**
     * عرض صفحة الأطباء
     */
    public function doctors()
    {$doctors = Doctors::with('specialization')->paginate(10);
        $specializations = Specialization::all();
        return view('dashboard.doctors.index', compact('doctors', 'specializations'));
        
    }

    /**
     * عرض صفحة إنشاء طبيب جديد
     */
    public function doctorsCreate()
    {
        $specializations = Specialization::all();
        $users = User::whereDoesntHave('roles', function($query) {
            $query->where('name', 'doctor');
        })->get();
        return view('dashboard.doctors.create', compact('specializations', 'users'));
    }

    /**
     * عرض صفحة تعديل طبيب
     */
    public function doctorsEdit($id)
    {
        $doctor = Doctors::findOrFail($id);
        $specializations = Specialization::all();
        return view('dashboard.doctors.edit', compact('doctor', 'specializations'));
    }

    /**
     * عرض صفحة الاختصاصات
     */
    public function specializations()
    {
        $specializations = Specialization::paginate(10);
        return view('dashboard.specializations.index', compact('specializations'));
    }

    /**
     * عرض صفحة إنشاء اختصاص جديد
     */
    public function specializationsCreate()
    {
        return view('dashboard.specializations.create');
    }

    /**
     * عرض صفحة تعديل اختصاص
     */
    public function specializationsEdit($id)
    {
        $specialization = Specialization::findOrFail($id);
        return view('dashboard.specializations.edit', compact('specialization'));
    }

    /**
     * عرض صفحة الشيفتات
     */
    public function shifts()
    {
        $shifts = Shift::with(['doctors.user', 'doctors.specialization'])->paginate(10);

        return view('dashboard.shifts.index', compact('shifts'));
    }

    /**
     * عرض صفحة إنشاء شيفت جديد
     */
    public function shiftsCreate()
    {
        $doctors = Doctors::all();
        $shifts = Shift::all();
        return view('dashboard.shifts.create', compact('doctors','shifts'));
    }

    /**
     * عرض صفحة تعديل شيفت
     */
    public function shiftsEdit($id)
    {
        $shift = Shift::findOrFail($id);
        $doctors = Doctors::all();
        return view('dashboard.shifts.edit', compact('shift', 'doctors'));
    }

    /**
     * عرض صفحة تعديل بيانات الشيفت نفسه
     */
    public function shiftsEditData($id)
    {
        $shift = Shift::findOrFail($id);
        return view('dashboard.shifts.edit_data', compact('shift'));
    }

    /**
     * عرض صفحة تعديل ارتباط الطبيب بالشيفت
     */
    public function shiftsEditDoctor($id)
    {
        $shift = Shift::findOrFail($id);
        $doctors = Doctors::with('user', 'specialization')->get();
        $shiftDoctors = $shift->doctors()->with('user', 'specialization')->get();
        $shifts = Shift::all(); // إضافة جميع الشيفتات للاختيار منها
        
        return view('dashboard.shifts.edit_doctor', compact('shift', 'doctors', 'shiftDoctors', 'shifts'));
    }

    /**
     * تحديث بيانات الشيفت نفسه
     */
    public function shiftsUpdateData(Request $request, $id)
    {
        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'shift_type' => 'required|string|unique:shifts,shift_type,' . $id,
            'start_break_time' => 'required|date_format:H:i',
            'end_break_time' => 'required|date_format:H:i|after:start_break_time',
        ]);

        $shift = Shift::findOrFail($id);
        $shift->update($request->only(['start_time', 'end_time', 'shift_type', 'start_break_time', 'end_break_time']));

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث بيانات الشيفت بنجاح'
        ]);
    }

    /**
     * تحديث ارتباط الطبيب بالشيفت
     */
    public function shiftsUpdateDoctor(Request $request, $id)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'days' => 'required|array',
            'days.*' => 'in:Saturday,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday',
            'action' => 'required|in:update,delete,add'
        ]);

        $shift = Shift::findOrFail($id);
        $doctorId = $request->doctor_id;
        $days = $request->days;
        $action = $request->action;

        if ($action === 'delete') {
            // إلغاء الارتباط
            $shift->doctors()->detach($doctorId);
            $message = 'تم إلغاء ارتباط الطبيب بالشيفت بنجاح';
        } elseif ($action === 'add') {
            // إضافة ارتباط جديد
            $shift->doctors()->attach($doctorId, ['days' => json_encode($days)]);
            $message = 'تم إضافة الطبيب للشيفت بنجاح';
        } else {
            // تحديث الأيام
            $existing = $shift->doctors()->where('doctor_id', $doctorId)->first();
            
            if ($existing) {
                $shift->doctors()->updateExistingPivot($doctorId, ['days' => json_encode($days)]);
                $message = 'تم تحديث أيام الشيفت للطبيب بنجاح';
            } else {
                $message = 'الطبيب غير مرتبط بهذا الشيفت';
            }
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * عرض صفحة المرضى
     */
    public function patients()
    {
        $patients = Patients::with('user')->paginate(10);
        
        // إضافة عدد المواعيد وآخر موعد لكل مريض
        foreach ($patients as $patient) {
            $appointments = Appointment::where('patient_id', $patient->id)->get();
            $patient->appointments_count = $appointments->count();
            $patient->appointments_max_date = $appointments->max('date');
        }
        
        return view('dashboard.patients.index', compact('patients'));
    }

    /**
     * عرض صفحة تعديل مريض
     */
    public function patientsEdit($id)
    {
        $patient = Patients::with('user')->findOrFail($id);
        return view('dashboard.patients.edit', compact('patient'));
    }

    /**
     * عرض صفحة المواعيد
     */
    public function appointments()
    {
        $appointments = Appointment::with(['doctor.user', 'patient.user'])->paginate(10);
        return view('dashboard.appointments.index', compact('appointments'));
    }

    /**
     * عرض صفحة التقارير
     */
    public function reports()
    {
        return view('dashboard.reports');
    }

    // ========== التوابع الجديدة ==========

    /**
     * إنشاء اختصاص جديد (مثل API)
     */
    public function createSpecialization(Request $request)
    {
        $lang = $request->header('lan', 'ar');
        $translator = new GoogleTranslate($lang);

        $request->validate([
            'image' => 'required|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg+xml|max:2048',
            'name' => 'required|string|unique:specializations,name',
        ]);

        try {
            $cloudinary = app(Cloudinary::class);
            $uploaded = $cloudinary->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'specializations']
            );
            $url = $uploaded['secure_url'];

            Specialization::create([
                'name' => $request->name,
                'path' => $url,
            ]);

            return response()->json([
                'success' => true,
                'message' => $translator->translate('تم إنشاء الاختصاص بنجاح.'),
                'data' => ['imageUrl' => $url]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $translator->translate('حدث خطأ أثناء إنشاء الاختصاص.')
            ], 500);
        }
    }

    /**
     * إنشاء طبيب جديد (مثل API)
     */
    public function createDoctor(Request $request)
    {
        $lang = $request->header('lan', 'ar');
        $translator = new GoogleTranslate($lang);

        $validated = $request->validate([
            'specialization_id' => 'required|exists:specializations,id',
            'consultation_duration' => 'required|integer|min:1|max:1440',
            'user_id' => 'required|exists:users,id',
            'bio' => 'required|string'
        ]);

        try {
            $user = User::find($validated['user_id']);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => $translator->translate('المستخدم غير موجود')
                ], 404);
            }

            if (!$user->hasRole('doctor')) {
                $user->assignRole('doctor');
            }

            $defaultPath = "https://res.cloudinary.com/.../specializations/default.png";
            if ($user->gender === 'أنثى') {
                $defaultPath = "https://res.cloudinary.com/.../specializations/default_female.png";
            }

            $doctor = Doctors::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'specialization_id' => $validated['specialization_id'],
                    'consultation_duration' => $validated['consultation_duration'],
                    'bio' => $validated['bio'],
                    'imageUrl' => $defaultPath
                ]
            );

            return response()->json([
                'success' => true,
                'message' => $translator->translate('تم حفظ بيانات الطبيب بنجاح')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $translator->translate('حدث خطأ أثناء حفظ بيانات الطبيب')
            ], 500);
        }
    }

    /**
     * إنشاء شيفت جديد (مثل API)
     */
    public function createShift(ShiftRequest $request)
    {
       
   

        $validated = $request->validated();

        $shift = Shift::create($validated);

        if ($shift) {
            return response()->json([
                'success' => true,
                'message' => 'تم انشاء الشيفت ب نجاح .'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم انشاء الشيفت .'
            ]);
        }
    }

    /**
     * إسناد شيفت إلى طبيب (مثل API)
     */
    public function assignShiftToDoctor(Request $request)
    {
 ;

        $request->validate([
            'doctor_id'   => 'required|exists:doctors,id',
            'shift_type'  => 'required|exists:shifts,shift_type',
            'days'        => 'required|array',
            'days.*'      => 'in:Saturday,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday',
        ]);

        try {
            $doctorId  = $request->doctor_id;
            $shiftType = $request->shift_type;
            $days      = $request->days;

            $shift = Shift::where('shift_type', $shiftType)->first();

            if (!$shift) {
                return response()->json([
                    'success' => false,
                    'message' => 'نوع الشيفت غير موجود.'
                ], 404);
            }

            $existing = DB::table('doctor_shift')
                ->where('doctor_id', $doctorId)
                ->where('shift_id', $shift->id)
                ->get();

            foreach ($existing as $record) {
                $existingDays = json_decode($record->days, true);
                if (!empty(array_intersect($existingDays, $days))) {
                    return response()->json([
                        'success' => false,
                        'message' => 'الطبيب مسند إليه هذا الشيفت بالفعل في يوم واحد أو أكثر من الأيام المختارة.'   
                    ], 422);
                }
            }

            Doctors::find($doctorId)->shifts()->attach($shift->id, [
                'days' => json_encode($days)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إسناد الشيفت إلى الطبيب بنجاح.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إسناد الشيفت.'
            ], 500);
        }
    }

    /**
     * الحصول على جميع الاختصاصات (مثل API)
     */
    public function getAllSpecializations()
    {
        $specializations = Specialization::all(['id', 'name', 'path']);

        if ($specializations->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على اختصاصات.',
                'data' => []
            ]);
        }

        $formatted = $specializations->map(fn($s) => [
            'id' => (string) $s->id,
            'name' => $s->name,
            'iconUrl' => $s->path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم جلب الاختصاصات بنجاح.',
            'data' => $formatted,
        ]);
    }

    /**
     * تحديث بيانات المريض
     */
    public function patientsUpdate(Request $request, $id)
    {
        $patient = Patients::with('user')->findOrFail($id);
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'number' => 'required|string|unique:users,number,' . $patient->user_id,
            'national_number' => 'nullable|string|max:255',
            'birth_day' => 'nullable|date',
            'gender' => 'nullable|in:ذكر,أنثى',
            'daily_doses_number' => 'nullable|integer|min:0|max:10',
            'taken_doses' => 'nullable|integer|max:10',
        ]);

        try {
            DB::beginTransaction();

            // تحديث بيانات المستخدم
            $patient->user->update([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'mother_name' => $request->mother_name,
                'number' => $request->number,
                'national_number' => $request->national_number,
                'birth_day' => $request->birth_day,
                'gender' => $request->gender,
            ]);

            // تحديث بيانات المريض
            $patient->update([
                'daily_doses_number' => $request->daily_doses_number ?? 0,
                'taken_doses' => $request->taken_doses ?? 0,
            ]);

            DB::commit();

            return redirect()->route('dashboard.patients.index')
                ->with('success', 'تم تحديث بيانات المريض بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث بيانات المريض: ' . $e->getMessage());
        }
    }
}
