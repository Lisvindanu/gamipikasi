<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Point;
use App\Services\PointService;

class GiveInitialPointsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pointService = new PointService();

        // Get the lead user to be the one giving points
        $leadUser = User::where('role', 'lead')->first();

        if (!$leadUser) {
            $this->command->error('❌ Lead user not found! Please seed users first.');
            return;
        }

        // Find all users with 0 points
        $usersWithNoPoints = User::where('total_points', 0)->get();

        if ($usersWithNoPoints->isEmpty()) {
            $this->command->info('✅ All users already have points!');
            return;
        }

        $this->command->info("Found {$usersWithNoPoints->count()} users with 0 points");
        $this->command->info("Giving 10 initial points to each user...\n");

        $successCount = 0;
        $failCount = 0;

        foreach ($usersWithNoPoints as $user) {
            try {
                // Give 10 points as initial welcome bonus in commitment category
                $pointService->addPoint(
                    userId: $user->id,
                    category: 'commitment',
                    value: 10,
                    givenBy: $leadUser->id,
                    note: 'Initial points - Welcome to GDGOC UNPAS!'
                );

                $this->command->info("✅ {$user->name} - Awarded 10 points");
                $successCount++;
            } catch (\Exception $e) {
                $this->command->error("❌ Failed to give points to {$user->name}: {$e->getMessage()}");
                $failCount++;
            }
        }

        $this->command->info("\n" . str_repeat('=', 50));
        $this->command->info("✅ Successfully awarded points to {$successCount} users");

        if ($failCount > 0) {
            $this->command->warn("⚠️  Failed to award points to {$failCount} users");
        }
    }
}
