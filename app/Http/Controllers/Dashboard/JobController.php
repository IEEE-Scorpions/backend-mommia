<?php

namespace App\Http\Controllers\Dashboard;

use Carbon\Carbon;
use App\Models\Job;
use App\Models\Employee;
use App\Exports\JobExport;
use App\Imports\JobImport;
use Illuminate\Http\Request;
use App\Exports\VacationExport;
use App\Imports\VacationImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class JobController extends Controller
{

    public function __construct(){
        $this->middleware(['permission:create_jobs'])->only('create');
        $this->middleware(['permission:read_jobs'])->only('index');
        $this->middleware(['permission:update_jobs'])->only('edit');
        $this->middleware(['permission:delete_jobs'])->only('destroy');
    }

    public $paginate_num = 10;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $owner = auth()->user()->hasRole("owner");

        $filters = remove_null_filters($request->all());

        if($owner){

            if($request->all()){

                $jobs = Job::where(function($query) use ($request){
                    $query->where("name", "like", "%".$request->search."%");
                    $query->orWhere("tasks", "like", "%".$request->search."%");
                 })
                 ->filterBy($filters);
            }else{
                $jobs = new Job;
            }


        }else{

            if($request->all()){
                $jobs = Job::where(function($query) use ($request){
                    $query->where("name", "like", "%".$request->search."%");
                    $query->orWhere("tasks", "like", "%".$request->search."%");
                })
                 ->filterBy($filters);
            }else{
                $jobs = new Job;
            }

        }


        if(!$jobs){
            return abort(500);
        }

        $jobs = $jobs->paginate($this->paginate_num);

        return view("dashboard.jobs.index")
        ->with("jobs", $jobs);
    }

   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("dashboard.jobs.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request,[
            'name' => 'required',
            'tasks' => 'required'
        ]);



        $data = [
            "name" => $request->name,
            "tasks" => $request->tasks,
            "active" => $request->active ?? 1,
            'created_by' => id(),
            'updated_by' => 0
        ];

        $job = Job::create($data);

        if(!$job){
            return abort(500, __("site.contact_support"));
        }

        session()->put('success',__('site.added_successfully'));

        return redirect()->route('dashboard.jobs.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $job = Job::findOrFail($id);


        return view("dashboard.jobs.edit",compact('job'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job)
    {
        $this->validate($request,[
            'name' => 'required',
            'tasks' => 'required'
        ]);



        $data = [
            "name" => $request->name,
            "tasks" => $request->tasks,
            "active" => $request->active ?? 1,
            'updated_by' => id()
        ];


        if(!$job->update($data)){
            return abort(500, __("site.contact_support"));
        }


        session()->put('success',__('site.updated_successfully'));

        return redirect()->route('dashboard.jobs.edit', $job->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        if(!$job->delete()){
            abort(500);
        }

        session()->put('success', __("site.deleted_successfully"));

        return redirect()->route("dashboard.jobs.index");
    }




    public function import_page(Request $request){
        return view('dashboard.jobs.import');
    }

    public function import(Request $request)
    {
        Excel::import(new JobImport, request()->file('jobs_csv'));
        session()->put('success', 'Imported Successfully!');
        return redirect()->route('dashboard.jobs.import');
    }

    public function export(Request $request)
    {
        return Excel::download(new JobExport, 'jobs.xlsx');
    }



}
