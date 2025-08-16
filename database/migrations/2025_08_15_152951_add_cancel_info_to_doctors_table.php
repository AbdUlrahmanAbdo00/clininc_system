<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCancelInfoToDoctorsTable extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->integer('cancel_count')->default(0)->after('consultation_duration');
            $table->timestamp('last_canceled_at')->nullable()->after('cancel_count');
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn(['cancel_count', 'last_canceled_at']);
        });
    }
}
