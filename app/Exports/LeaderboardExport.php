<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LeaderboardExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected $departmentId;
    protected $limit;

    public function __construct($departmentId = null, $limit = 50)
    {
        $this->departmentId = $departmentId;
        $this->limit = $limit;
    }

    public function collection()
    {
        $query = User::with(['department', 'badges'])
            ->where('role', 'member')
            ->where('total_points', '>', 0);

        if ($this->departmentId) {
            $query->where('department_id', $this->departmentId);
        }

        return $query->orderBy('total_points', 'desc')
            ->limit($this->limit)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Rank',
            'Name',
            'Department',
            'Total Points',
            'Badges Earned',
            'Status',
        ];
    }

    public function map($member): array
    {
        static $rank = 0;
        $rank++;

        $status = match(true) {
            $member->total_points >= 100 => 'Outstanding',
            $member->total_points >= 50 => 'Excellent',
            $member->total_points >= 25 => 'Good',
            default => 'Fair',
        };

        return [
            $rank,
            $member->name,
            $member->department ? $member->department->name : '-',
            $member->total_points,
            $member->badges->count(),
            $status,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FBBC05']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }

    public function title(): string
    {
        return 'Leaderboard';
    }
}
