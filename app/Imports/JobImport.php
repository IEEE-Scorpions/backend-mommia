<?php

namespace App\Imports;

use App\Models\Job;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JobImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Job([
            'name' => $row['name'],
            'tasks' => $row['tasks'],
            "active" => @$row['active'] ?? 1,
            "created_by" => @$row['created_by'] ?? id(),
            "updated_by" => @$row['updated_by'] ?? 0,
        ]);
    }
}
