<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceExport implements FromCollection, WithHeadings
{
    protected $attendance;

    public function __construct(Collection $attendance)
    {
        $this->attendance = $attendance;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->attendance->map(function ($att) {
            return [
                'ID' => $att['id'],
                'Date' => $att['date'],
                'Time' => $att['time'],
                'Student Name' => $att['student']['name'] ?? '',
                'Organization Name' => $att['organization']['name'] ?? '',
                'Classroom Name' => $att['classRoom']['name'] ?? '',
                'Approve Status' => $att['approve_status'] ? 'Approved' : 'Not Approved',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Date',
            'Time',
            'Student Name',
            'Organization Name',
            'Classroom Name',
            'Approve Status',
        ];
    }
}
