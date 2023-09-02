<?php

namespace App\Imports;

use App\Models\TourPackage;
use Maatwebsite\Excel\Concerns\ToModel;

class TourPackageImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new TourPackage([
            //
        ]);
    }
}
