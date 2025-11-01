<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UpdateNewMembersOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mapping department ke position name
        $positionMap = [
            'Media Creative' => 'staff_media_creative',
            'Human Resource' => 'staff_hr',
            'Public Relationship' => 'staff_public_relation',
            'Event' => 'staff_event',
            'Curriculum Web' => 'staff_web_developer',
            'Curriculum ML' => 'staff_machine_learning',
            'Curriculum IoT' => 'staff_iot_development',
            'Curriculum Game' => 'staff_game_development',
        ];

        // New members data with emails
        $newMembersEmails = [
            'raffaazhar396@gmail.com',
            'muhamad.fatur.rahaman@gmail.com',
            'mildakhaerunnisa379@gmail.com',
            'rahhmadini2020@gmail.com',
            'muhamadrobby24@gmail.com',
            'aaallaaamm03@gmail.com',
            'biagiiarchie@gmail.com',
            'aliditia123@gmail.com',
            'ellenaplidazalni@gmail.com',
            'rezanurjamanr@gmail.com',
            'nada.p.rukanda@gmail.com',
            'morenowisesa@gmail.com',
            'nurfatimahhh1603z@gmail.com',
            'andhikadimari02@gmail.com',
            'emmir.233040054@mail.unpas.ac.id',
            'aryasaputra1304@gmail.com',
            'ddzaky46@gmail.com',
            'aryaraihanhanif@gmail.com',
            'irsan.taufik38@gmail.com',
        ];

        $this->command->info('🔄 Updating organization positions for new members...');
        $this->command->newLine();

        // Start order from 16 (after existing 15 positions)
        $currentOrder = 16;
        $updated = 0;

        foreach ($newMembersEmails as $email) {
            $user = User::where('email', $email)->first();

            if (!$user) {
                $this->command->warn("⚠️  User not found: {$email}");
                continue;
            }

            // Skip if already has organization position
            if ($user->organization_position) {
                $this->command->warn("⏭️  Skipping {$user->name} - already has position: {$user->organization_position}");
                continue;
            }

            // Get department name
            $departmentName = $user->department ? $user->department->name : null;

            if (!$departmentName || !isset($positionMap[$departmentName])) {
                $this->command->error("❌ No position mapping for department: {$departmentName} (User: {$user->name})");
                continue;
            }

            // Get position name from mapping
            $positionName = $positionMap[$departmentName];

            // Update user
            $user->update([
                'organization_position' => $positionName,
                'organization_order' => $currentOrder,
            ]);

            $this->command->info("✅ Updated: {$user->name} → {$positionName} (Order: {$currentOrder})");
            $currentOrder++;
            $updated++;
        }

        $this->command->newLine();
        $this->command->info("📊 Summary:");
        $this->command->info("   ✅ Updated: {$updated} members");
        $this->command->newLine();
        $this->command->info('🎉 All new members are now visible in the organization page!');
    }
}
