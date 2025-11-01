<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Department;

class NewCoreTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('gdgoc2024');

        // Get departments
        $departments = [
            'MEDCRE' => Department::where('name', 'Media Creative')->first(),
            'HR' => Department::where('name', 'Human Resource')->first(),
            'PR' => Department::where('name', 'Public Relationship')->first(),
            'EVENT' => Department::where('name', 'Event')->first(),
            'WEB' => Department::where('name', 'Curriculum Web')->first(),
            'ML' => Department::where('name', 'Curriculum ML')->first(),
            'IOT' => Department::where('name', 'Curriculum IoT')->first(),
        ];

        // New Core Team Members Data
        $members = [
            [
                'name' => 'Muhammad Raffa Azhar Fadhul',
                'email' => 'raffaazhar396@gmail.com',
                'division' => 'MEDCRE',
            ],
            [
                'name' => 'Muhammad Fatur Rahman',
                'email' => 'muhamad.fatur.rahaman@gmail.com',
                'division' => 'MEDCRE',
            ],
            [
                'name' => 'Milda Khaerunnisa',
                'email' => 'mildakhaerunnisa379@gmail.com',
                'division' => 'MEDCRE',
            ],
            [
                'name' => 'Fitriyani Rahmadini',
                'email' => 'rahhmadini2020@gmail.com',
                'division' => 'HR',
            ],
            [
                'name' => 'M Robby A',
                'email' => 'muhamadrobby24@gmail.com',
                'division' => 'PR',
            ],
            [
                'name' => 'Muhamad Nur Salam',
                'email' => 'aaallaaamm03@gmail.com',
                'division' => 'EVENT',
            ],
            [
                'name' => 'Biagi Archie Fais',
                'email' => 'biagiiarchie@gmail.com',
                'division' => 'WEB',
            ],
            [
                'name' => 'Reiza Mohamad Aliditia',
                'email' => 'aliditia123@gmail.com',
                'division' => 'MEDCRE',
            ],
            [
                'name' => 'Ellen Aplida Zalni',
                'email' => 'ellenaplidazalni@gmail.com',
                'division' => 'ML',
            ],
            [
                'name' => 'Reza Nurjaman',
                'email' => 'rezanurjamanr@gmail.com',
                'division' => 'ML',
            ],
            [
                'name' => 'Nada Putri Agilan Rukanda',
                'email' => 'nada.p.rukanda@gmail.com',
                'division' => 'ML',
            ],
            [
                'name' => 'Moreno Wisesa Dafa Gumilar',
                'email' => 'morenowisesa@gmail.com',
                'division' => 'EVENT',
            ],
            [
                'name' => 'Chalida Rahma Listy Hidayat',
                'email' => 'chalidarlh@gmail.com',
                'division' => 'HR',
            ],
            [
                'name' => 'Nurfatimah',
                'email' => 'nurfatimahhh1603z@gmail.com',
                'division' => 'PR',
            ],
            [
                'name' => 'Andhika Ashari',
                'email' => 'andhikadimari02@gmail.com',
                'division' => 'MEDCRE',
            ],
            [
                'name' => 'Emmir Fahrezi',
                'email' => 'emmir.233040054@mail.unpas.ac.id',
                'division' => 'MEDCRE',
            ],
            [
                'name' => 'Arya Saputra',
                'email' => 'aryasaputra1304@gmail.com',
                'division' => 'IOT',
            ],
            [
                'name' => 'Dwi Dzaky Wibowo',
                'email' => 'ddzaky46@gmail.com',
                'division' => 'MEDCRE',
            ],
            [
                'name' => 'Arya Raihan Hanif',
                'email' => 'aryaraihanhanif@gmail.com',
                'division' => 'PR',
            ],
            [
                'name' => 'Irsan Moch. Taufik Febrian',
                'email' => 'irsan.taufik38@gmail.com',
                'division' => 'IOT',
            ],
        ];

        $this->command->info('🚀 Adding new Core Team members...');
        $this->command->newLine();

        $added = 0;
        $skipped = 0;

        foreach ($members as $memberData) {
            // Check if user already exists
            $existingUser = User::where('email', $memberData['email'])->first();

            if ($existingUser) {
                $this->command->warn("⏭️  Skipping {$memberData['name']} ({$memberData['email']}) - already exists");
                $skipped++;
                continue;
            }

            // Get department
            $department = $departments[$memberData['division']] ?? null;

            if (!$department) {
                $this->command->error("❌ Department not found for {$memberData['division']}");
                continue;
            }

            // Create user
            User::create([
                'name' => $memberData['name'],
                'email' => $memberData['email'],
                'password' => $defaultPassword,
                'role' => 'member',
                'department_id' => $department->id,
                'total_points' => 0,
            ]);

            $this->command->info("✅ Added: {$memberData['name']} ({$memberData['division']})");
            $added++;
        }

        $this->command->newLine();
        $this->command->info("📊 Summary:");
        $this->command->info("   ✅ Added: {$added} members");
        $this->command->info("   ⏭️  Skipped: {$skipped} members (already exist)");
        $this->command->newLine();
        $this->command->info('🔑 Default password for all new members: gdgoc2024');
    }
}
