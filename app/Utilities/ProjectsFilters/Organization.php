<?php

namespace App\Utilities\ProjectsFilters;

use App\Utilities\FilterContract;

class Organization implements FilterContract
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function handle($value): void
    {
        $this->query->where('organization_id', $value);
    }
}
