<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class EmployeesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Employee::select([
            'id',
            "name",
            "code",
            "gender",
            "fingerprint_code",
            "national_id",
            "qualification",
            "graduation_year",
            "degree_class",
            "job_id",
            "birth_date",
            "vacations_balance",
            "primary_salary",
            "hiring_date",
            "leaving_date",
            "reason_of_leaving",
            "notes"
        ])->get();
    }

    public function headings(): array
    {
        return [
            'id',
            "name",
            "code",
            "gender",
            "fingerprint_code",
            "national_id",
            "qualification",
            "graduation_year",
            "degree_class",
            "job_id",
            "birth_date",
            "vacations_balance",
            "primary_salary",
            "hiring_date",
            "leaving_date",
            "reason_of_leaving",
            "notes"
        ];
    }
}
