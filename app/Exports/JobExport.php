<?php

namespace App\Exports;

use App\Models\Job;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class JobExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Job::select([
            'id',
            'name',
            'tasks'
        ])->get();
    }

    public function headings(): array
    {
        return [
            'id',
            "name",
            "tasks"
        ];
    }
}
