<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            // Point-based milestones
            [
                'name' => 'Newcomer',
                'description' => 'Raih 10 poin pertama',
                'icon' => '🌱',
                'emoji' => '🌱',
                'criteria_type' => 'points',
                'criteria_value' => 10,
                'auto_award' => true,
            ],
            [
                'name' => 'Rising Star',
                'description' => 'Raih 25 total poin',
                'icon' => '⭐',
                'emoji' => '⭐',
                'criteria_type' => 'points',
                'criteria_value' => 25,
                'auto_award' => true,
            ],
            [
                'name' => 'Active Contributor',
                'description' => 'Raih 50 total poin',
                'icon' => '⚡',
                'emoji' => '⚡',
                'criteria_type' => 'points',
                'criteria_value' => 50,
                'auto_award' => true,
            ],
            [
                'name' => 'Top Performer',
                'description' => 'Raih 75 total poin',
                'icon' => '🔥',
                'emoji' => '🔥',
                'criteria_type' => 'points',
                'criteria_value' => 75,
                'auto_award' => true,
            ],
            [
                'name' => 'Superstar',
                'description' => 'Raih 100 total poin',
                'icon' => '🌟',
                'emoji' => '🌟',
                'criteria_type' => 'points',
                'criteria_value' => 100,
                'auto_award' => true,
            ],
            [
                'name' => 'Legend',
                'description' => 'Raih 150 total poin',
                'icon' => '👑',
                'emoji' => '👑',
                'criteria_type' => 'points',
                'criteria_value' => 150,
                'auto_award' => true,
            ],

            // Category-specific badges
            [
                'name' => 'Team Player',
                'description' => 'Raih 30+ poin collaboration',
                'icon' => '👥',
                'emoji' => '👥',
                'criteria_type' => 'collaboration',
                'criteria_value' => 30,
                'auto_award' => true,
            ],
            [
                'name' => 'Reliable',
                'description' => 'Raih 25+ poin responsibility',
                'icon' => '💪',
                'emoji' => '💪',
                'criteria_type' => 'responsibility',
                'criteria_value' => 25,
                'auto_award' => true,
            ],
            [
                'name' => 'Innovator',
                'description' => 'Raih 40+ poin initiative',
                'icon' => '🧠',
                'emoji' => '🧠',
                'criteria_type' => 'initiative',
                'criteria_value' => 40,
                'auto_award' => true,
            ],
            [
                'name' => 'Dedicated',
                'description' => 'Raih 35+ poin commitment',
                'icon' => '🎯',
                'emoji' => '🎯',
                'criteria_type' => 'commitment',
                'criteria_value' => 35,
                'auto_award' => true,
            ],

            // Assessment-based
            [
                'name' => 'Consistent',
                'description' => 'Terima 20+ penilaian',
                'icon' => '📊',
                'emoji' => '📊',
                'criteria_type' => 'assessments',
                'criteria_value' => 20,
                'auto_award' => true,
            ],
            [
                'name' => 'Veteran',
                'description' => 'Terima 50+ penilaian',
                'icon' => '🎖️',
                'emoji' => '🎖️',
                'criteria_type' => 'assessments',
                'criteria_value' => 50,
                'auto_award' => true,
            ],

            // Manual badges (special achievements)
            [
                'name' => 'Perfect Record',
                'description' => 'Zero violations selama 3 bulan berturut-turut',
                'icon' => '✨',
                'emoji' => '✨',
                'criteria_type' => 'manual',
                'criteria_value' => null,
                'auto_award' => false,
            ],
            [
                'name' => 'Event Champion',
                'description' => 'Memimpin event besar dengan sukses',
                'icon' => '🏆',
                'emoji' => '🏆',
                'criteria_type' => 'manual',
                'criteria_value' => null,
                'auto_award' => false,
            ],
            [
                'name' => 'Community Hero',
                'description' => 'Kontribusi luar biasa untuk komunitas',
                'icon' => '🦸',
                'emoji' => '🦸',
                'criteria_type' => 'manual',
                'criteria_value' => null,
                'auto_award' => false,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['name' => $badge['name']],
                $badge
            );
        }
    }
}
