<?php

namespace App\Imports;

use App\Models\Pharaoh;
use Maatwebsite\Excel\Concerns\ToModel;

class PharaohImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Pharaoh([
            //
        ]);
    }
}
