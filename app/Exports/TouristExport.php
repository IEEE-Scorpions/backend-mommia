<?php

namespace App\Exports;

use App\Models\Tourist;
use Maatwebsite\Excel\Concerns\FromCollection;

class TouristExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Tourist::all();
    }
}
