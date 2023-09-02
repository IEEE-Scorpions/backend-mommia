<?php

namespace App\Models;

use App\Utilities\FilterBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeFilterBy($query, $filters)
    {
        $namespace = 'App\Utilities\OrganizationsFilters';
        $filter = new FilterBuilder($query, $filters, $namespace);
        return $filter->apply();
    }

    public function getImgAttribute(){
        if($this->pic == '' || $this->pic == 'default.png' || $this->pic == 'organization-default.png'){
            return asset('pics/organization-default.png');
        }
        return asset('uploads/organizations/pics/' . $this->pic);
    }
}
