<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('ALTER TABLE appointments ALTER COLUMN cancled DROP DEFAULT');

        DB::statement('ALTER TABLE appointments ALTER COLUMN cancled TYPE TEXT USING cancled::text');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE appointments ALTER COLUMN cancled TYPE BOOLEAN USING cancled::boolean');
    }
};

