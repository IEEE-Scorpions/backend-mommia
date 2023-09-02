<?php

namespace App\Exports;

use App\Models\Pharaoh;
use Maatwebsite\Excel\Concerns\FromCollection;

class PharaohExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Pharaoh::all();
    }
}
