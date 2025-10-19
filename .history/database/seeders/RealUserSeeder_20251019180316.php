<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Department;

class RealUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('gdgoc2024');

        // Get departments
        $departments = [
            'hr' => Department::where('name', 'Human Resource')->first(),
            'ml' => Department::where('name', 'Curriculum ML')->first(),
            'event' => Department::where('name', 'Event')->first(),
            'media' => Department::where('name', 'Media Creative')->first(),
            'pr' => Department::where('name', 'Public Relationship')->first(),
            'web' => Department::where('name', 'Curriculum Web')->first(),
            'iot' => Department::where('name', 'Curriculum IoT')->first(),
            'game' => Department::where('name', 'Curriculum Game')->first(),
        ];

        $users = [
            // 1. Lead
            [
                'name' => 'Narapati Keysa Anandi',
                'email' => 'narapati.lead@gdgoc.id',
                'password' => $defaultPassword,
                'role' => 'lead',
                'department_id' => null,
                'total_points' => 0,
            ],

            // 2. Co-Lead
            [
                'name' => 'Muhammad Sufi Nadziffa Ridwan',
                'email' => 'nadziffa123@gmail.com',
                'password' => $defaultPassword,
                'role' => 'co-lead',
                'department_id' => null,
                'total_points' => 0,
            ],

            // 3. Treasurer (Bendahara)
            [
                'name' => 'Icha Aprilia Putri',
                'email' => 'ptriaprili34@gmail.com',
                'password' => $defaultPassword,
                'role' => 'bendahara',
                'department_id' => null,
                'total_points' => 0,
            ],

            // 4. Secretary
            [
                'name' => 'Annisa Septiyani',
                'email' => 'anisaseptiani475@gmail.com',
                'password' => $defaultPassword,
                'role' => 'secretary',
                'department_id' => null,
                'total_points' => 0,
            ],

            // 5. Head of HR
            [
                'name' => 'Lisvindanu',
                'email' => 'Lisvindanu015@gmail.com',
                'password' => $defaultPassword,
                'role' => 'head',
                'department_id' => $departments['hr']->id ?? null,
                'total_points' => 0,
            ],

            // 6. Head of Machine Learning
            [
                'name' => 'Muhammad Fauzan Dwi Putera',
                'email' => 'mfauzandwiputera10@gmail.com',
                'password' => $defaultPassword,
                'role' => 'head',
                'department_id' => $departments['ml']->id ?? null,
                'total_points' => 0,
            ],

            // 7. Head of Event
            [
                'name' => 'Desi Hafita Ashri',
                'email' => 'desihafitaashri.dha@gmail.com',
                'password' => $defaultPassword,
                'role' => 'head',
                'department_id' => $departments['event']->id ?? null,
                'total_points' => 0,
            ],

            // 8. Head of Public Relation
            [
                'name' => 'Rayhan Alfa Rezki',
                'email' => 'rayhanalfarezki@gmail.com',
                'password' => $defaultPassword,
                'role' => 'head',
                'department_id' => $departments['pr']->id ?? null,
                'total_points' => 0,
            ],

            // 9. Head of Game Dev
            [
                'name' => 'Raden Indra Prawirajaya',
                'email' => 'raden.233040043@mail.unpas.ac.id',
                'password' => $defaultPassword,
                'role' => 'head',
                'department_id' => $departments['game']->id ?? null,
                'total_points' => 0,
            ],

            // 10. Head of IoT Dev
            [
                'name' => 'Naufal Zul Faza',
                'email' => 'naufalzul45@gmail.com',
                'password' => $defaultPassword,
                'role' => 'head',
                'department_id' => $departments['iot']->id ?? null,
                'total_points' => 0,
            ],

            // 11. Head of Media Creative
            [
                'name' => 'Valdric Abirama Pranaja Dandi',
                'email' => 'valdricapd@gmail.com',
                'password' => $defaultPassword,
                'role' => 'head',
                'department_id' => $departments['media']->id ?? null,
                'total_points' => 0,
            ],

            // 12. Head of Web Dev
            [
                'name' => 'Rafli Ramdhani',
                'email' => 'rafli@kodingin.id',
                'password' => $defaultPassword,
                'role' => 'head',
                'department_id' => $departments['web']->id ?? null,
                'total_points' => 0,
            ],

            // 13. Event Staff
            [
                'name' => 'Muhamad Marsa Nur Jaman',
                'email' => 'mmarsa2435@gmail.com',
                'password' => $defaultPassword,
                'role' => 'member',
                'department_id' => $departments['event']->id ?? null,
                'total_points' => 0,
            ],

            // 14. Head of Curriculum (oversees all curriculum departments)
            [
                'name' => 'Bhadrika Aryaputra Hermawan',
                'email' => 'bhadrika.aryaputra@gmail.com',
                'password' => $defaultPassword,
                'role' => 'head',
                'department_id' => null, // Manages all curriculum departments
                'total_points' => 0,
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        $this->command->info('✅ Real users seeded successfully!');
        $this->command->info('📧 Default password for all users: gdgoc2024');
    }
}
