<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Employee;
use App\Utilities\FilterBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vacation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    public function scopeFilterBy($query, $filters)
    {
        $namespace = 'App\Utilities\VacationsFilters';
        $filter = new FilterBuilder($query, $filters, $namespace);
        return $filter->apply();
    }

    public function getDaysAttribute(){
        $start = Carbon::parse($this->start);
        $end = Carbon::parse($this->end);
        $days = $start->diffInDays($end);
        return $days;
    }


    public function getVacationTypeNameAttribute(){
        $vacation_type = $this->vacation_type;
        $vacation_type_name = match ($vacation_type) {
            1 => __("site.yearly_vacation"),
            2 => __("site.sick_vacation"),
            3 => __("site.relative_die_vacation"),
            4 => __("site.haj_vacation"),
            5 => __("site.omra_vacation"),
            6 => __("site.other"),
            default => __("site.other"),
        };
        return $vacation_type_name;
    }
}
