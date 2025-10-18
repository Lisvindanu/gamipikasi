<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Leadership (No Department)
        User::create([
            'name' => 'GDGoC Lead',
            'email' => 'lead@gdgoc.id',
            'password' => Hash::make('password'),
            'role' => 'lead',
            'department_id' => null,
            'total_points' => 0,
        ]);

        User::create([
            'name' => 'GDGoC Co-Lead',
            'email' => 'colead@gdgoc.id',
            'password' => Hash::make('password'),
            'role' => 'co-lead',
            'department_id' => null,
            'total_points' => 0,
        ]);

        User::create([
            'name' => 'Bendahara',
            'email' => 'bendahara@gdgoc.id',
            'password' => Hash::make('password'),
            'role' => 'bendahara',
            'department_id' => null,
            'total_points' => 0,
        ]);

        User::create([
            'name' => 'Secretary',
            'email' => 'secretary@gdgoc.id',
            'password' => Hash::make('password'),
            'role' => 'secretary',
            'department_id' => null,
            'total_points' => 0,
        ]);

        // 8 Department Heads
        User::create([
            'name' => 'HR Head',
            'email' => 'hr.head@gdgoc.id',
            'password' => Hash::make('password'),
            'role' => 'head',
            'department_id' => 1, // Human Resource
            'total_points' => 0,
        ]);

        User::create([
            'name' => 'Media Creative Head',
            'email' => 'medcre.head@gdgoc.id',
            'password' => Hash::make('password'),
            'role' => 'head',
            'department_id' => 2, // Media Creative
            'total_points' => 0,
        ]);

        User::create([
            'name' => 'Event Head',
            'email' => 'event.head@gdgoc.id',
            'password' => Hash::make('password'),
            'role' => 'head',
            'department_id' => 3, // Event
            'total_points' => 0,
        ]);

        User::create([
            'name' => 'PR Head',
            'email' => 'pr.head@gdgoc.id',
            'password' => Hash::make('password'),
            'role' => 'head',
            'department_id' => 4, // Public Relationship
            'total_points' => 0,
        ]);

        User::create([
            'name' => 'Web Head',
            'email' => 'web.head@gdgoc.id',
            'password' => Hash::make('password'),
            'role' => 'head',
            'department_id' => 5, // Curriculum Web
            'total_points' => 0,
        ]);

        User::create([
            'name' => 'IoT Head',
            'email' => 'iot.head@gdgoc.id',
            'password' => Hash::make('password'),
            'role' => 'head',
            'department_id' => 6, // Curriculum IoT
            'total_points' => 0,
        ]);

        User::create([
            'name' => 'ML Head',
            'email' => 'ml.head@gdgoc.id',
            'password' => Hash::make('password'),
            'role' => 'head',
            'department_id' => 7, // Curriculum ML
            'total_points' => 0,
        ]);

        User::create([
            'name' => 'Game Head',
            'email' => 'game.head@gdgoc.id',
            'password' => Hash::make('password'),
            'role' => 'head',
            'department_id' => 8, // Curriculum Game
            'total_points' => 0,
        ]);

        // HR Members (can assign points)
        User::create([
            'name' => 'HR Member 1',
            'email' => 'hr1@gdgoc.id',
            'password' => Hash::make('password'),
            'role' => 'member',
            'department_id' => 1,
            'total_points' => 0,
        ]);

        User::create([
            'name' => 'HR Member 2',
            'email' => 'hr2@gdgoc.id',
            'password' => Hash::make('password'),
            'role' => 'member',
            'department_id' => 1,
            'total_points' => 0,
        ]);

        // Other Department Members
        User::create([
            'name' => 'Media Creative Member',
            'email' => 'medcre@gdgoc.id',
            'password' => Hash::make('password'),
            'role' => 'member',
            'department_id' => 2,
            'total_points' => 0,
        ]);

        User::create([
            'name' => 'Event Member',
            'email' => 'event@gdgoc.id',
            'password' => Hash::make('password'),
            'role' => 'member',
            'department_id' => 3,
            'total_points' => 0,
        ]);

        User::create([
            'name' => 'PR Member',
            'email' => 'pr@gdgoc.id',
            'password' => Hash::make('password'),
            'role' => 'member',
            'department_id' => 4,
            'total_points' => 0,
        ]);

        User::create([
            'name' => 'Web Developer',
            'email' => 'webdev@gdgoc.id',
            'password' => Hash::make('password'),
            'role' => 'member',
            'department_id' => 5,
            'total_points' => 0,
        ]);

        User::create([
            'name' => 'IoT Developer',
            'email' => 'iotdev@gdgoc.id',
            'password' => Hash::make('password'),
            'role' => 'member',
            'department_id' => 6,
            'total_points' => 0,
        ]);

        User::create([
            'name' => 'ML Engineer',
            'email' => 'mlengineer@gdgoc.id',
            'password' => Hash::make('password'),
            'role' => 'member',
            'department_id' => 7,
            'total_points' => 0,
        ]);

        User::create([
            'name' => 'Game Developer',
            'email' => 'gamedev@gdgoc.id',
            'password' => Hash::make('password'),
            'role' => 'member',
            'department_id' => 8,
            'total_points' => 0,
        ]);
    }
}
