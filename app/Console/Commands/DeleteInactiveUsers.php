<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class DeleteInactiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete-inactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete users who have been inactive for more than 30 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $usersToDelete = User::where('status', 'inactive')
            ->whereNotNull('scheduled_deletion_at')
            ->where('scheduled_deletion_at', '<=', $now)
            ->get();

        $count = $usersToDelete->count();

        if ($count === 0) {
            $this->info('No inactive users to delete.');
            return 0;
        }

        foreach ($usersToDelete as $user) {
            $this->info("Deleting user ID {$user->id} ({$user->email})");
            $user->delete();
        }

        $this->info("Deleted {$count} inactive user(s).");

        return 0;
    }
}
