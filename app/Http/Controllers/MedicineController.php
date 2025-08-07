<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicines;
use Illuminate\Support\Facades\DB;

class MedicineController extends Controller
{
    public function addMedicineToDB(Request $request)
    {
        $lan = $request->header('lan', 'en');
        $messages = [
            'en' => [
                'medicine_exists' => 'This medicine already exists.',
                'fetched_successfully' => 'Fetched successfully.',
            ],
            'ar' => [
                'medicine_exists' => 'هذا الدواء موجود بالفعل.',
                'fetched_successfully' => 'تم جلب البيانات بنجاح.',
            ],
        ];
        $msg = $messages[$lan] ?? $messages['en'];

        $validated = $request->validate([
            'medicine_name' => 'required|string|max:255'
        ]);

        $medicine = Medicines::where('name', $request->medicine_name)->first();

        if ($medicine) {
            return response()->json([
                'message' => $msg['medicine_exists']
            ], 200);
        }

        DB::transaction(function () use ($validated) {
            Medicines::create([
                'name' => $validated['medicine_name']
            ]);
        });

        $medicine = Medicines::where('name', $request->medicine_name)->first();

        return response()->json([
            'success' => true,
            'message' => $msg['fetched_successfully'],
            'data' => [
                'id' => $medicine->id,
                'name' => $medicine->name
            ]
        ], 200);
    }

    public function getMedicinesBySubstring(Request $request)
    {
        $lan = $request->header('lan', 'en');
        $messages = [
            'en' => [
                'no_medicines' => 'No medicines found.',
                'fetched_successfully' => 'Fetched successfully.',
            ],
            'ar' => [
                'no_medicines' => 'لم يتم العثور على أدوية.',
                'fetched_successfully' => 'تم جلب البيانات بنجاح.',
            ],
        ];
        $msg = $messages[$lan] ?? $messages['en'];

        $request->validate([
            'substring' => 'required|string'
        ]);

        $medicines = Medicines::where('name', 'LIKE', "%{$request->substring}%")
                        ->select('id', 'name')
                        ->get();

        if ($medicines->isEmpty()) {
            return response()->json([
                'message' => $msg['no_medicines']
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => $msg['fetched_successfully'],
            'data' => $medicines
        ], 200);
    }
}
