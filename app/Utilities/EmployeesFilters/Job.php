<?php

namespace App\Utilities\EmployeesFilters;

use App\Utilities\FilterContract;

class Job implements FilterContract
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function handle($value): void
    {
        $this->query->where('job_id', $value);
    }
}
