<?php

namespace App\Exports;

use App\Models\Vacation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VacationExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Vacation::select([
            'id',
            'employee_id',
            'request_date',
            'vacation_type',
            'start',
            'end',
            'reason'
        ])->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'employee_id',
            'request_date',
            'vacation_type',
            'start',
            'end',
            'reason'
        ];
    }
}
