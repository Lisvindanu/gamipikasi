<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Human Resource',
                'description' => 'Manajemen SDM & Rekrutmen',
            ],
            [
                'name' => 'Media Creative',
                'description' => 'Desain & Konten Kreatif',
            ],
            [
                'name' => 'Event',
                'description' => 'Perencanaan & Pelaksanaan Acara',
            ],
            [
                'name' => 'Public Relationship',
                'description' => 'Komunikasi & Partnership',
            ],
            [
                'name' => 'Curriculum Web',
                'description' => 'Web Development & Frontend',
            ],
            [
                'name' => 'Curriculum IoT',
                'description' => 'Internet of Things & Hardware',
            ],
            [
                'name' => 'Curriculum ML',
                'description' => 'Machine Learning & AI',
            ],
            [
                'name' => 'Curriculum Game',
                'description' => 'Game Development & Design',
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
