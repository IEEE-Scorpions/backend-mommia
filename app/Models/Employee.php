<?php

namespace App\Models;

use App\Models\Job;
use App\Models\Vacation;
use App\Utilities\FilterBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getImgAttribute(){
        if($this->pic == '' || $this->pic == 'default.png' || $this->pic == 'employee-default.png'){
            return asset('pics/employee-default.png');
        }
        return asset('uploads/employees/pics/' . $this->pic);
    }

    public function scopeFilterBy($query, $filters)
    {
        $namespace = 'App\Utilities\EmployeesFilters';
        $filter = new FilterBuilder($query, $filters, $namespace);
        return $filter->apply();
    }


    public function getRemainingVacationsAttribute(){
        $balance = intval($this->vacations_balance);
        $vacations = intval($this->vacations()->count());
        return $balance - $vacations;
    }

    public function vacations(){
        return $this->hasMany(Vacation::class);
    }

    public function job(){
        return $this->belongsTo(Job::class);
    }
}
