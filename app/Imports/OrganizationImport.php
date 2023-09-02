<?php

namespace App\Imports;

use App\Models\Organization;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrganizationImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Organization([
            'name' => $row['name'],
            'email' => $row['email'],
            'address' => $row['address'],
            'website' => $row['website'],
            'facebook_url' => $row['facebook_url'],
            "active" => @$row['active'] ?? 1,
            "created_by" => @$row['created_by'] ?? id(),
            "updated_by" => @$row['updated_by'] ?? 0,
        ]);
    }
}
