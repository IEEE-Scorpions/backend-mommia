<?php

namespace App\Imports;

use App\Models\Vacation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VacationImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Vacation([
            'employee_id' => $row['employee_id'],
            'request_date' => $row['request_date'],
            'vacation_type' => $row['vacation_type'],
            'start' => $row['start'],
            'end' => $row['end'],
            'reason' => $row['reason'],
            "active" => @$row['active'] ?? 1,
            "created_by" => @$row['created_by'] ?? id(),
            "updated_by" => @$row['updated_by'] ?? 0,
        ]);
    }
}
