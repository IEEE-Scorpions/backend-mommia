<?php

namespace App\Exports;

use App\Models\TouristPackage;
use Maatwebsite\Excel\Concerns\FromCollection;

class TouristPackageExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return TouristPackage::all();
    }
}
