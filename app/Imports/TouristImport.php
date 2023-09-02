<?php

namespace App\Imports;

use App\Models\Tourist;
use Maatwebsite\Excel\Concerns\ToModel;

class TouristImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Tourist([
            //
        ]);
    }
}
