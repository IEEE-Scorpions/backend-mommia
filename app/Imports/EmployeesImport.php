<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class EmployeesImport implements ToModel, WithHeadingRow ,WithUpserts
{



    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        if (Employee::where('code', $row['code'])->exists()) {
            // Modify the value if it exists
            $row['code'] = generateCode(5);
        }

        return new Employee([
            "name" => $row['name'],
            "code" => $row['code'],
            "gender" => $row['gender'],
            "fingerprint_code" => $row['fingerprint_code'],
            "national_id" => $row['national_id'],
            "qualification" => $row['qualification'],
            "graduation_year" => $row['graduation_year'],
            "degree_class" => $row['degree_class'],
            "job_id" => $row['job_id'],
            "birth_date" => $row['birth_date'],
            "vacations_balance" => $row['vacations_balance'] ?? NULL,
            "primary_salary" => $row['primary_salary'],
            "hiring_date" => $row['hiring_date'],
            "leaving_date" => $row['leaving_date'],
            "reason_of_leaving" => @$row['reason_of_leaving'] ?? NULL,
            "notes" => @$row['notes'] ?? NULL,
            "active" => @$row['active'] ?? 1,
            "created_by" => @$row['created_by'] ?? id(),
            "updated_by" => @$row['updated_by'] ?? 0,

        ]);
    }

    public function uniqueBy()
    {
        return 'code';
    }
}
