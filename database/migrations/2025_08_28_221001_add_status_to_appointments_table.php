<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // غيّر نوع العمود
        DB::statement('ALTER TABLE appointments ALTER COLUMN cancled TYPE TEXT USING cancled::text');
        // احذف القيمة الافتراضية
        DB::statement('ALTER TABLE appointments ALTER COLUMN cancled DROP DEFAULT');
    }

    public function down(): void
    {
        // رجعه Boolean
        DB::statement('ALTER TABLE appointments ALTER COLUMN cancled TYPE BOOLEAN USING cancled::boolean');
        DB::statement('ALTER TABLE appointments ALTER COLUMN cancled SET DEFAULT false');
    }
};
