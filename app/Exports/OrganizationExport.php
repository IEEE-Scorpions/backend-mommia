<?php

namespace App\Exports;

use App\Models\Organization;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrganizationExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Organization::select([
            'id',
            'name',
            'email',
            'address',
            'website',
            'facebook_url',
        ])->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'email',
            'address',
            'website',
            'facebook_url'
        ];
    }

}
