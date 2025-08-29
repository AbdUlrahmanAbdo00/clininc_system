<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class KillLocks extends Command
{
    protected $signature = 'db:kill-locks';
    protected $description = 'Kill all PostgreSQL transactions that are idle';

    public function handle()
    {
        // احصل على PID الخاص بالجلسة الحالية
        $currentPid = DB::selectOne("SELECT pg_backend_pid() as pid")->pid;

        // احصل كل الجلسات في Transaction ما عدا الجلسة الحالية
        $sessions = DB::select("
            SELECT pid
            FROM pg_stat_activity
            WHERE pid <> ? AND state = 'idle in transaction'
        ", [$currentPid]);

        foreach ($sessions as $session) {
            // انهاء كل جلسة معلقة
            DB::statement("SELECT pg_terminate_backend(?)", [$session->pid]);
        }

        $this->info('All idle transactions have been terminated successfully!');
        
    }

}
