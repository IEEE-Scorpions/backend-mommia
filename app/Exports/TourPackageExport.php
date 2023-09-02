<?php

namespace App\Exports;

use App\Models\TourPackage;
use Maatwebsite\Excel\Concerns\FromCollection;

class TourPackageExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return TourPackage::all();
    }
}
