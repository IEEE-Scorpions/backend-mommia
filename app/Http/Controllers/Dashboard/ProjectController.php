<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Exports\ProjectExport;
use App\Imports\ProjectImport;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use Maatwebsite\Excel\Facades\Excel;

class ProjectController extends Controller
{

    public function __construct(){
        $this->middleware(['permission:create_projects'])->only('create');
        $this->middleware(['permission:read_projects'])->only('index');
        $this->middleware(['permission:update_projects'])->only('edit');
        $this->middleware(['permission:delete_projects'])->only('destroy');
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

                $projects = Project::where(function($query) use ($request){
                    $query->where("name", "like", "%".$request->search."%");
                    $query->orWhere("tasks", "like", "%".$request->search."%");
                 })
                 ->filterBy($filters);
            }else{
                $projects = new Project;
            }


        }else{

            if($request->all()){
                $projects = Project::where(function($query) use ($request){
                    $query->where("name", "like", "%".$request->search."%");
                    $query->orWhere("tasks", "like", "%".$request->search."%");
                })
                 ->filterBy($filters);
            }else{
                $projects = new Project;
            }

        }


        if(!$projects){
            return abort(500);
        }

        $projects = $projects->paginate($this->paginate_num);

        $organizations = Organization::all();

        return view("dashboard.projects.index")
        ->with("organizations", $organizations)
        ->with("projects", $projects);
    }

   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organizations = Organization::all();
        return view("dashboard.projects.create", compact('organizations'));
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
            'tasks' => 'required',
            'organization_id' => 'required|exists:organizations,id',
        ]);



        $data = [
            "name" => $request->name,
            "tasks" => $request->tasks,
            "organization_id" => $request->organization_id,
            "active" => $request->active ?? 1,
            'created_by' => id(),
            'updated_by' => 0
        ];

        $project = Project::create($data);

        if(!$project){
            return abort(500, __("site.contact_support"));
        }

        session()->put('success',__('site.added_successfully'));

        return redirect()->route('dashboard.projects.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $organizations = Organization::all();

        return view("dashboard.projects.edit",compact('project','organizations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $this->validate($request,[
            'name' => 'required',
            'tasks' => 'required',
            'organization_id' => 'required|exists:organizations,id',
        ]);



        $data = [
            "name" => $request->name,
            "tasks" => $request->tasks,
            "organization_id" => $request->organization_id,
            "active" => $request->active ?? 1,
            'updated_by' => id()
        ];


        if(!$project->update($data)){
            return abort(500, __("site.contact_support"));
        }


        session()->put('success',__('site.updated_successfully'));

        return redirect()->route('dashboard.projects.edit', $project->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        if(!$project->delete()){
            abort(500);
        }

        session()->put('success', __("site.deleted_successfully"));

        return redirect()->route("dashboard.projects.index");
    }




    public function import_page(Request $request){
        return view('dashboard.projects.import');
    }

    public function import(Request $request)
    {
        Excel::import(new ProjectImport, request()->file('projects_csv'));
        session()->put('success', 'Imported Successfully!');
        return redirect()->route('dashboard.projects.import');
    }

    public function export(Request $request)
    {
        return Excel::download(new ProjectExport, 'projects.xlsx');
    }



}
