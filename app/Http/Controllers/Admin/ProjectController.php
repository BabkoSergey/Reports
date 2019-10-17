<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use Validator;

use App\Repository\ProjectsRepository;

class ProjectController extends Controller
{
    private $projects;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProjectsRepository $projects)
    {
        $this->middleware('permission:admin panel');
        $this->middleware('permission:show projects', ['only' => ['index', 'projectsDTAjax', 'show']]);
        $this->middleware('permission:add projects|append resources', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit projects', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete projects', ['only' => ['destroy']]);
        
        $this->projects = $projects;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('admin.project.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.project.create_form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateRules = [
                'name' => 'required|unique:projects,name',
                'status' => 'nullable|boolean',
            ];
        
        $input = $request->all();
        $validator = Validator::make($input, $validateRules);
        
        if ($validator->fails())
            return response()->json(['error' => $validator->errors()], 422);
        
        $project = $this->projects->create($input);
        
        if(!$project)
            return response()->json(['error'=>['form' => __('Error saving form')]], 422);
           
        return response()->json(['success' => __('Project added successfully'), 'project' => $project, 'type' => 'store', 'resource' => 'project']);    
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = $this->projects->find($id);
        
        if(!$project)
            return redirect()->route('projects.index')
                        ->with('errores', [__('Record not found')]);
        
        return view('admin.project.show',compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = $this->projects->find($id);
        
        if(!$project)
            return response()->json(['error'=>['project' => __('Record not found')]], 422);
                                
        return view('admin.project.edit_form',compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $projectCheck = $this->projects->find($id);
        if(!$projectCheck)
            return response()->json(['error'=>['project' => __('Record not found')]], 422);
           
        $validateRules = [                
                'status' => 'required|boolean',
            ];          
        
        if($projectCheck->name != $request->get('name')){
            $validateRules['name'] = 'required|unique:projects,name';
        }
        
        $input = $request->all();
        $validator = Validator::make($input, $validateRules);
        
        if ($validator->fails())
            return response()->json(['error' => $validator->errors()], 422);
        
        $project = $this->projects->update($projectCheck, $input);
        if(!$project)
            return response()->json(['error'=>['form' => __('Error saving form')]], 422);
        
        return response()->json(['success' => __('Project updated successfully'), 'project' => $project, 'type' => 'update']);  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!$this->projects->delete($id))
            return response()->json(['error'=>['form' => __('Error delete project')]], 422);
                
        return response()->json(['success' => __('Project deleted successfully')]);
    }
    
    /**
     * Projects datatable Ajax fetch
     *
     * @return json $out
     */    
    public function projectsDTAjax()
    {
        $projects = $this->projects->all();
                            
        $out = datatables()->of($projects)                
                ->addColumn('actions', '')
                ->toJson();

        return $out;
    }
}
