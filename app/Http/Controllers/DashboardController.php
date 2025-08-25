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

        return view('dashboard.index', compact('stats'));
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
        $shifts = Shift::with('doctor')->paginate(10);

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
     * عرض صفحة المرضى
     */
    public function patients()
    {
        $patients = Patients::with('user')->paginate(10);
        return view('dashboard.patients.index', compact('patients'));
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
}
