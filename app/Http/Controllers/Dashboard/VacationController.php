<?php

namespace App\Http\Controllers\Dashboard;

use Carbon\Carbon;
use App\Models\Vacation;
use Illuminate\Http\Request;
use App\Exports\VacationExport;
use App\Imports\VacationImport;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use Maatwebsite\Excel\Facades\Excel;

class VacationController extends Controller
{

    public function __construct(){
        $this->middleware(['permission:create_vacations'])->only('create');
        $this->middleware(['permission:read_vacations'])->only('index');
        $this->middleware(['permission:update_vacations'])->only('edit');
        $this->middleware(['permission:delete_vacations'])->only('destroy');
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
                $vacations = Vacation::whereHas('employee', function($query) use($request){
                    return $query->where("name", "like", "%".$request->search."%")
                    ->orWhere("code", "like", "%".$request->search."%");
                 })
                 ->orWhere("reason", "like", "%".$request->search."%")
                 ->filterBy($filters);
            }else{
                $vacations = new Vacation;
            }


        }else{

            if($request->all()){
                $vacations = Vacation::whereHas('employee', function($query) use($request){
                    return $query->where("name", "like", "%".$request->search."%")
                    ->orWhere("code", "like", "%".$request->search."%");
                 })
                 ->orWhere("reason", "like", "%".$request->search."%")
                 ->filterBy($filters);
            }else{
                $vacations = new Vacation;
            }

        }


        if(!$vacations){
            return abort(500);
        }

        $vacations = $vacations->paginate($this->paginate_num);

        return view("dashboard.vacations.index")
        ->with("vacations", $vacations);
    }

   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::all();
        return view("dashboard.vacations.create", compact('employees'));
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
            'employee_id' => 'required|exists:employees,id',
            'vacation_type' => 'required',
            'reason' => 'required',
            'end' => 'required|date'
        ]);



        $data = [
            "employee_id" => $request->employee_id,
            "request_date" => $request->request_date ?? Carbon::now(),
            "vacation_type" => $request->vacation_type,
            "start" => $request->start ?? Carbon::now(),
            "end" => $request->end,
            "reason" => $request->reason,

            "active" => $request->active ?? 1,
            'created_by' => id(),
            'updated_by' => 0
        ];




        $vacation = Vacation::create($data);

        if(!$vacation){
            return abort(500, __("site.contact_support"));
        }

        session()->put('success',__('site.added_successfully'));

        return redirect()->route('dashboard.vacations.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vacation = Vacation::findOrFail($id);
        $employees = Employee::all();

        return view("dashboard.vacations.edit",compact('vacation','employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vacation $vacation)
    {
        $this->validate($request,[
            'employee_id' => 'exists:vacations,id',
            'vacation_type' => 'required',
            'reason' => 'required',
            'end' => 'required|date'
        ]);



        $data = [
            "employee_id" => $request->employee_id,
            "request_date" => $request->request_date ?? Carbon::now(),
            "vacation_type" => $request->vacation_type,
            "start" => $request->start ?? Carbon::now(),
            "end" => $request->end,
            "reason" => $request->reason,

            "active" => $request->active ?? 1,
            'updated_by' => id()
        ];



        if(!$vacation->update($data)){
            return abort(500, __("site.contact_support"));
        }


        session()->put('success',__('site.updated_successfully'));

        return redirect()->route('dashboard.vacations.edit', $vacation->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vacation $vacation)
    {
        if(!$vacation->delete()){
            abort(500);
        }

        session()->put('success', __("site.deleted_successfully"));

        return redirect()->route("dashboard.vacations.index");
    }




    public function import_page(Request $request){
        return view('dashboard.vacations.import');
    }

    public function import(Request $request)
    {
        Excel::import(new VacationImport, request()->file('vacations_csv'));
        session()->put('success', 'Imported Successfully!');
        return redirect()->route('dashboard.vacations.import');
    }

    public function export(Request $request)
    {
        return Excel::download(new VacationExport, 'vacations.xlsx');
    }



}

