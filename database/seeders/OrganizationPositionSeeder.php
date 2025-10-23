<?php

namespace Database\Seeders;

use App\Models\OrganizationPosition;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing positions
        OrganizationPosition::truncate();

        // Data struktur organisasi berdasarkan data asli
        $organizationData = [
            // Core Leadership
            ['email' => 'narapatikeysa00@gmail.com', 'position_type' => 'core', 'position_name' => 'lead', 'order' => 1],
            ['email' => 'nadziffa123@gmail.com', 'position_type' => 'core', 'position_name' => 'co_lead', 'order' => 2],
            ['email' => 'ptriaprili34@gmail.com', 'position_type' => 'core', 'position_name' => 'bendahara', 'order' => 3],
            ['email' => 'anisaseptiani475@gmail.com', 'position_type' => 'core', 'position_name' => 'secretary', 'order' => 4],

            // Core Team
            ['email' => 'Lisvindanu015@gmail.com', 'position_type' => 'core', 'position_name' => 'head_of_human_resource', 'order' => 5],
            ['email' => 'desihafitaashri.dha@gmail.com', 'position_type' => 'core', 'position_name' => 'head_of_event', 'order' => 6],
            ['email' => 'rayhanalfarezki@gmail.com', 'position_type' => 'core', 'position_name' => 'head_of_public_relation', 'order' => 7],
            ['email' => 'valdricapd@gmail.com', 'position_type' => 'core', 'position_name' => 'head_of_media_creative', 'order' => 8],
            ['email' => 'mmarsa2435@gmail.com', 'position_type' => 'core', 'position_name' => 'staff_event', 'order' => 9],

            // Tech Team
            ['email' => 'mfauzandwiputera10@gmail.com', 'position_type' => 'core', 'position_name' => 'head_of_machine_learning', 'order' => 10],
            ['email' => 'rafli@kodingin.id', 'position_type' => 'core', 'position_name' => 'head_of_web_developer', 'order' => 11],
            ['email' => 'dhika@kodingin.id', 'position_type' => 'core', 'position_name' => 'head_of_curriculum_developer', 'order' => 12],
            ['email' => 'raden.233040043@mail.unpas.ac.id', 'position_type' => 'core', 'position_name' => 'head_of_game_development', 'order' => 13],
            ['email' => 'naufalzul45@gmail.com', 'position_type' => 'core', 'position_name' => 'head_of_iot_development', 'order' => 14],
        ];

        foreach ($organizationData as $data) {
            $user = User::where('email', $data['email'])->first();

            if ($user) {
                OrganizationPosition::create([
                    'user_id' => $user->id,
                    'position_type' => $data['position_type'],
                    'position_name' => $data['position_name'],
                    'order' => $data['order'],
                ]);
            } else {
                // Log jika user tidak ditemukan
                echo "Warning: User with email {$data['email']} not found.\n";
            }
        }
    }
}
