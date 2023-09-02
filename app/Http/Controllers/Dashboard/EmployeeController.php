<?php

namespace App\Http\Controllers\Dashboard;

use Dirape\Token\Token;
use App\Models\Employee;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Exports\EmployeesExport;
use App\Imports\EmployeesImport;
use App\Http\Controllers\Controller;
use App\Models\Job;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{

    public function __construct(){
        $this->middleware(['permission:create_employees'])->only('create');
        $this->middleware(['permission:read_employees'])->only('index');
        $this->middleware(['permission:update_employees'])->only('edit');
        $this->middleware(['permission:delete_employees'])->only('destroy');
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
                $employees = Employee::where(function($query) use ($request){
                    $query->where("name", "like", "%".$request->search."%");
                    $query->orWhere("national_id", "like", "%".$request->search."%");
                    $query->orWhere("code", "like", "%".$request->search."%");
                 })
                 ->orWherehas('job', function($query) use($request){
                    return $query->where('tasks', 'like', '%' .$request->search .'%');
                 })
                 ->filterBy($filters);
            }else{
                $employees = new Employee;
            }


        }else{

            if($request->all()){
                $employees = Employee::where(function($query) use ($request){
                    $query->where("name", "like", "%".$request->search."%");
                    $query->orWhere("national_id", "like", "%".$request->search."%");
                    $query->orWhere("code", "like", "%".$request->search."%");
                 })
                 ->orWherehas('job', function($query) use($request){
                    return $query->where('tasks', 'like', '%' .$request->search .'%');
                 })
                 ->filterBy($filters);
            }else{
                $employees = new Employee;
            }

        }


        if(!$employees){
            return abort(500);
        }

        $employees = $employees->paginate($this->paginate_num);
        $jobs = Job::all();

        return view("dashboard.employees.index")
        ->with("jobs", $jobs)
        ->with("employees", $employees);
    }

   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jobs = Job::all();
        return view("dashboard.employees.create", compact('jobs'));
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
            'national_id' => [
                'required',
                Rule::unique("employees", "national_id")
            ],

            'fingerprint_code' => [
                'required',
                Rule::unique("employees", "fingerprint_code")
            ],
            'qualification' => 'required',
            'graduation_year' => 'required|size:4',
            'degree_class' => 'required',
            'job_id' => 'required|exists:jobs,id',
            'birth_date' => 'required|date',
            'hiring_date' => 'required|date',
            'leaving_date' => 'required|date',
        ]);



        $data = [
            "name" => $request->name,
            "code" => $this->checkCode(generateCode(5)),
            "gender" => $request->gender,
            "fingerprint_code" => $request->fingerprint_code,
            "national_id" => $request->national_id,
            "qualification" => $request->qualification,
            "graduation_year" => $request->graduation_year,
            "degree_class" => $request->degree_class,
            "job_id" => $request->job_id,
            "birth_date" => $request->birth_date,
            "vacations_balance" => $request->vacations_balance ?? NULL,
            "primary_salary" => $request->primary_salary,
            "hiring_date" => $request->hiring_date,
            "leaving_date" => $request->leaving_date,
            "reason_of_leaving" => $request->reason_of_leaving ?? NULL,
            "notes" => $request->notes ?? NULL,
            "active" => $request->active ?? 1,
            'created_by' => id(),
            'updated_by' => 0
        ];


        if($request->file('pic')){
            $file= $request->file('pic');
            $filename= 'author-'.id().'-'.date('YmdHi') . time() .'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/employees/pics/'), $filename);
            $data['pic']= $filename;
        }else{
            $data['pic'] = 'employee-default.png';
        }



        $employee = Employee::create($data);

        if(!$employee){
            return abort(500, __("site.contact_support"));
        }

        session()->put('success',__('site.added_successfully'));

        return redirect()->route('dashboard.employees.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);

        $jobs = Job::all();

        return view("dashboard.employees.edit",compact('employee','jobs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        $this->validate($request,[
            'name' => 'required',
            'national_id' => [
                'required',
                Rule::unique("employees", "national_id")->ignore($employee->id)
            ],

            'fingerprint_code' => [
                'required',
                Rule::unique("employees", "fingerprint_code")->ignore($employee->id)
            ],
            'qualification' => 'required',
            'graduation_year' => 'required|size:4',
            'degree_class' => 'required',
            'job_id' => 'required|exists:jobs,id',
            'birth_date' => 'required|date',
            'hiring_date' => 'required|date',
            'leaving_date' => 'required|date',
        ]);



        $data = [
            "name" => $request->name,
            "code" => $this->checkCode(generateCode(5)),
            "gender" => $request->gender,
            "fingerprint_code" => $request->fingerprint_code,
            "national_id" => $request->national_id,
            "qualification" => $request->qualification,
            "graduation_year" => $request->graduation_year,
            "degree_class" => $request->degree_class,
            "job_id" => $request->job_id,
            "birth_date" => $request->birth_date,
            "vacations_balance" => $request->vacations_balance ?? NULL,
            "primary_salary" => $request->primary_salary,
            "hiring_date" => $request->hiring_date,
            "leaving_date" => $request->leaving_date,
            "reason_of_leaving" => $request->reason_of_leaving ?? NULL,
            "notes" => $request->notes ?? NULL,
            "active" => $request->active ?? 1,
            'updated_by' => id()
        ];


        if($request->file('pic')){

            $file= $request->file('pic');
            $filename= 'author-'.id().'-'.date('YmdHi') . time() .'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/employees/pics/'), $filename);
            $data['pic']= $filename;
        }else{
            if($employee->pic == ''){
                $data['pic'] = 'employee-default.png';
            }
        }

        if(!$employee->update($data)){
            return abort(500, __("site.contact_support"));
        }


        session()->put('success',__('site.updated_successfully'));

        return redirect()->route('dashboard.employees.edit', $employee->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        if(!$employee->delete()){
            abort(500);
        }

        session()->put('success', __("site.deleted_successfully"));

        return redirect()->route("dashboard.employees.index");
    }


    public function checkCode($code){
        if(Employee::where('code', $code)->exists()){
            return generateCode($code);
        }else{
            return $code;
        }
    }

    public function import_page(Request $request){
        return view('dashboard.employees.import');
    }

    public function import(Request $request)
    {
        Excel::import(new EmployeesImport, request()->file('employees_csv'));
        session()->put('success', 'Imported Successfully!');
        return redirect()->route('dashboard.employees.import');
    }

    public function export(Request $request)
    {
        return Excel::download(new EmployeesExport, 'employees.xlsx');
    }



}
