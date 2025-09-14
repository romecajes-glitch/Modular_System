<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DeleteExpiredInactiveUsers implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $now = Carbon::now();

        $usersToDelete = User::where('status', 'inactive')
            ->whereNotNull('scheduled_deletion_at')
            ->where('scheduled_deletion_at', '<=', $now)
            ->get();

        $count = $usersToDelete->count();

        if ($count === 0) {
            Log::info('No inactive users to delete.');
            return;
        }

        foreach ($usersToDelete as $user) {
            Log::info("Deleting expired inactive user ID {$user->id} ({$user->email})");
            $user->delete();
        }

        Log::info("Deleted {$count} expired inactive user(s).");
    }
}
