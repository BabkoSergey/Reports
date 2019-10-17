<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use Validator;

use App\Rules\CheckResourceTypesRule;

use App\Repository\ProjectsRepository;
use App\Repository\EstimateRepository;
use App\Repository\TasksRepository;

class ResourceController extends Controller
{
    private $projects;
    
    private $estimates;
    
    private  $tasks;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProjectsRepository $projects, TasksRepository $tasks, EstimateRepository $estimates)
    {
        $this->middleware('permission:admin panel');        
        $this->middleware('permission:append resources', ['only' => ['create', 'store']]);
        
        $this->projects = $projects;
        $this->estimates = $estimates;
        $this->tasks = $tasks;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['error'=>['method' => __('Method not available!')]], 422);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(!$request->get('resource'))
            return response()->json(['error'=>['resource' => __('Resource type does not exist!')]], 422);
        
        $validateRules = [ 'resource' => new CheckResourceTypesRule($this->tasks->getTypes())];
        
        $validator = Validator::make($request->all(), $validateRules);        
        if ($validator->fails())
            return response()->json(['error' => $validator->errors()], 422);
        
        $full = false;
        
        switch ($request->get('resource')){
            case 'project':
                return view('admin.project.create_form', compact('full'));
                break;
            
            case 'estimate':
                return view('admin.estimate.create_form', compact('full'));
                break;
                
            default :
                return response()->json(['error'=>['resource' => __('Resource type does not exist!')]], 422);
                break;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response()->json(['error'=>['method' => __('Method not available!')]], 422);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(['error'=>['method' => __('Method not available!')]], 422);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response()->json(['error'=>['method' => __('Method not available!')]], 422);
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
        return response()->json(['error'=>['method' => __('Method not available!')]], 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->json(['error'=>['method' => __('Method not available!')]], 422);
    }
    
}
