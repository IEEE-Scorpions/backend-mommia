<?php

namespace App\Imports;

use App\Models\TouristPackage;
use Maatwebsite\Excel\Concerns\ToModel;

class TouristPackageImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new TouristPackage([
            //
        ]);
    }
}
