<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MembersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $departmentId;

    public function __construct($departmentId = null)
    {
        $this->departmentId = $departmentId;
    }

    public function collection()
    {
        $query = User::with('department')
            ->whereIn('role', ['member', 'head']);

        if ($this->departmentId) {
            $query->where('department_id', $this->departmentId);
        }

        return $query->orderBy('total_points', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Name',
            'Email',
            'Role',
            'Department',
            'Total Points',
            'Join Date',
        ];
    }

    public function map($member): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $member->name,
            $member->email,
            ucfirst($member->role),
            $member->department ? $member->department->name : '-',
            $member->total_points,
            $member->created_at->format('d M Y'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4285F4']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }
}
