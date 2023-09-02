<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Job;
use App\Models\User;
use App\Models\Employee;
use App\Models\Vacation;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Project;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $admins_count = User::whereRoleIs('admin')->orWhereRoleIs('owner')->get()->count();
        return view("dashboard.index",compact([
            'admins_count'
        ]));
    }
}
