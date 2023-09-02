<?php

namespace App\Utilities\VacationsFilters;

use App\Utilities\FilterContract;

class Type implements FilterContract
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function handle($value): void
    {
        $this->query->where('vacation_type', $value);
    }
}
