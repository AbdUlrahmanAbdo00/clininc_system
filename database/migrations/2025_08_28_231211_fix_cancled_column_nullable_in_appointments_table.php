<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('ALTER TABLE appointments ALTER COLUMN cancled DROP NOT NULL');
        DB::statement('ALTER TABLE appointments ALTER COLUMN cancled DROP DEFAULT');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE appointments ALTER COLUMN cancled SET NOT NULL');
        DB::statement('ALTER TABLE appointments ALTER COLUMN cancled SET DEFAULT false');
    }
};