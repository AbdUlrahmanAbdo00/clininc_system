<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicines;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class MedicineController extends Controller
{
    // This function to add medicine to DB
    public function addMedicineToDB (Request $request) {
        $validated = $request->validate([
            'medicine_name' => 'required|string|max:255'
        ]);

        $medicine = Medicines::where('name', $request->medicine_name)->first();

        if ($medicine) {
            return response()->json([
                'message' => 'this medicine is already exists'
            ], 500);
        }

        DB::transaction(function () use ($validated) {
            Medicines::create([
                'name' => $validated['medicine_name']
            ]);
        }) ;

        $medicine = Medicines::where('name', $request->medicine_name)->first();

        return response()->json([
            'medicine_id' => $medicine->id,
            'medicine_name' => $medicine->name
        ], 200);
    }

    // This function to get all medicines have a specific substring
    public function getMedicinesBySubstring (Request $request) {
        $request->validate([
            'substring' => 'required|string'
        ]);

        $medicines = Medicines::where('name', 'LIKE', "%{$request->substring}%")
                        ->select('id', 'name')
                        ->get();
    
        if($medicines->isEmpty()) {
            return response()->json([
                'message' => 'no medicines found'
            ], 200);
        }

        return response()->json([
            'medicines' => $medicines
        ], 200);
    }
}
