<?php

namespace App\Imports;

use App\Models\TourGuide;
use Maatwebsite\Excel\Concerns\ToModel;

class TourGuideImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new TourGuide([
            //
        ]);
    }
}
