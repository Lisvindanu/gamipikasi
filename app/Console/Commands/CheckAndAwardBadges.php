<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\BadgeService;
use Illuminate\Console\Command;

class CheckAndAwardBadges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'badges:check-and-award
                            {--user-id= : Specific user ID to check}
                            {--dry-run : Show what would be awarded without actually awarding}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and award badges to users based on their current points and achievements';

    protected $badgeService;

    /**
     * Create a new command instance.
     */
    public function __construct(BadgeService $badgeService)
    {
        parent::__construct();
        $this->badgeService = $badgeService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $userId = $this->option('user-id');

        if ($isDryRun) {
            $this->warn('🔍 DRY RUN MODE - No badges will actually be awarded');
        }

        // Get users to process
        if ($userId) {
            $users = User::where('id', $userId)->get();
            if ($users->isEmpty()) {
                $this->error("User with ID {$userId} not found!");
                return 1;
            }
        } else {
            $users = User::all();
        }

        $this->info("Processing {$users->count()} user(s)...\n");

        $totalAwarded = 0;
        $progressBar = $this->output->createProgressBar($users->count());

        foreach ($users as $user) {
            $progressBar->advance();

            // Get user's current badges
            $currentBadgeIds = $user->badges()->pluck('badges.id')->toArray();

            if ($isDryRun) {
                // Dry run - check what would be awarded
                $eligibleBadges = $this->badgeService->getEligibleBadges($user);
                $newBadges = $eligibleBadges->filter(function ($badge) use ($currentBadgeIds) {
                    return !in_array($badge->id, $currentBadgeIds);
                });

                if ($newBadges->isNotEmpty()) {
                    $this->newLine();
                    $this->line("  👤 {$user->name} (ID: {$user->id}, Points: {$user->total_points})");
                    foreach ($newBadges as $badge) {
                        $this->line("    ✓ Would receive: {$badge->emoji} {$badge->name}");
                        $totalAwarded++;
                    }
                }
            } else {
                // Actually award badges
                $awarded = $this->badgeService->checkAndAwardBadges($user->id);
                if ($awarded > 0) {
                    $totalAwarded += $awarded;
                    $this->newLine();
                    $this->line("  ✓ {$user->name}: Awarded {$awarded} badge(s)");
                }
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        if ($isDryRun) {
            $this->info("✅ Dry run complete! {$totalAwarded} badge(s) would be awarded.");
            $this->line("\nRun without --dry-run to actually award the badges:");
            $this->line("  php artisan badges:check-and-award");
        } else {
            $this->info("✅ Complete! Awarded {$totalAwarded} badge(s) to users.");
        }

        return 0;
    }
}
