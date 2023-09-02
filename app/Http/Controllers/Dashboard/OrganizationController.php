<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Exports\OrganizationExport;
use App\Imports\OrganizationImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class OrganizationController extends Controller
{

    public function __construct(){
        $this->middleware(['permission:create_organizations'])->only('create');
        $this->middleware(['permission:read_organizations'])->only('index');
        $this->middleware(['permission:update_organizations'])->only('edit');
        $this->middleware(['permission:delete_organizations'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $owner = auth()->user()->hasRole("owner");

        if($owner){

            if($request->all()){
                $organizations = Organization::where("name", "like", "%".$request->search."%")
                 ->orWhere("email", "like", "%".$request->search."%")
                 ->paginate(10);
            }else{
                $organizations = Organization::paginate(10);
            }


        }else{

            if($request->all()){
                $organizations = Organization::where("name", "like", "%".$request->search."%")
                ->orWhere("email", "like", "%".$request->search."%")
                ->paginate(10);
            }else{
                $organizations = Organization::paginate(10);
            }

        }


        if(!$organizations){
            return abort(500);
        }

        return view("dashboard.organizations.index")
        ->with("organizations", $organizations);
    }

   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("dashboard.organizations.create");
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
            'email' => 'required|unique:organizations',
            'address' => 'required'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'website' => $request->website ?: 'not_set',
            'facebook_url' => $request->facebook_url ?: 'not_set',
            "active" => $request->active ?? 1,
            'created_by' => id(),
            'updated_by' => 0
        ];


        if($request->file('pic')){
            $file= $request->file('pic');
            $filename= 'author-'.id().'-'.date('YmdHi') . time() .'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/organizations/pics/'), $filename);
            $data['pic']= $filename;
        }else{
            $data['pic'] = 'organization-default.png';
        }

        $organization = Organization::create($data);

        if(!$organization){
            return abort(500, __("site.contact_support"));
        }

        session()->put('success',__('site.added_successfully'));

        return redirect()->route('dashboard.organizations.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $organization = Organization::findOrFail($id);

        return view("dashboard.organizations.edit",compact('organization'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organization $organization)
    {
        $this->validate($request,[
            'name' => 'required',
            'email' => [
                'required',
                Rule::unique("organizations")->ignore($organization->id)
            ],
            'address' => 'required'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'website' => $request->website ?: 'not_set',
            'facebook_url' => $request->facebook_url ?: 'not_set',
            "active" => $request->active ?? 1,
            'updated_by' => id()
        ];

        $old_photo = $organization->pic;


        if($request->file('pic')){

            $file= $request->file('pic');
            $filename= 'author-'.id().'-'.date('YmdHi') . time() .'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/organizations/pics/'), $filename);
            $data['pic']= $filename;
        }else{
            if($organization->pic == ''){
                $data['pic'] = 'organization-default.png';
            }
        }



        if(!$organization->update($data)){
            return abort(500, __("site.contact_support"));
        }

        session()->put('success',__('site.updated_successfully'));

        return redirect()->route('dashboard.organizations.edit', $organization->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization)
    {
        if(!$organization->delete()){
            abort(500);
        }

        session()->put('success', __("site.deleted_successfully"));

        return redirect()->route("dashboard.organizations.index");
    }

    public function import_page(Request $request){
        return view('dashboard.organizations.import');
    }

    public function import(Request $request)
    {
        Excel::import(new OrganizationImport, request()->file('organizations_csv'));
        session()->put('success', 'Imported Successfully!');
        return redirect()->route('dashboard.organizations.import');
    }

    public function export(Request $request)
    {
        return Excel::download(new OrganizationExport, 'organizations.xlsx');
    }



}
