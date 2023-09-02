<?php

namespace App\Models;

use App\Utilities\FilterBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeFilterBy($query, $filters)
    {
        $namespace = 'App\Utilities\ProjectsFilters';
        $filter = new FilterBuilder($query, $filters, $namespace);
        return $filter->apply();
    }

    public function organization(){
        return $this->belongsTo(Organization::class);
    }
}

