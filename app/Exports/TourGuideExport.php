<?php

namespace App\Exports;

use App\Models\TourGuide;
use Maatwebsite\Excel\Concerns\FromCollection;

class TourGuideExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return TourGuide::all();
    }
}
