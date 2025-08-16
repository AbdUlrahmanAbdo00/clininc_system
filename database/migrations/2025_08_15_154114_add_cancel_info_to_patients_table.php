<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->integer('cancel_count')->default(0)->after('user_id');
            $table->timestamp('last_canceled_at')->nullable()->after('cancel_count');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['cancel_count', 'last_canceled_at']);
        });
    }
};
