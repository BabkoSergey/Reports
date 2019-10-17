<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;

use App\Rules\CheckResourceTypesRule;
use App\Rules\CheckResourceRule;

use App\Repository\DevReportsRepository;
use App\Repository\TasksRepository;
use App\Repository\ProjectsRepository;
use App\Repository\EstimateRepository;

use App\User;

class DevReportController extends Controller
{
    private $reports;
    
    private $projects;
    
    private $estimates;
    
    private $tasks;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(DevReportsRepository $reports, TasksRepository $tasks, ProjectsRepository $projects, EstimateRepository $estimates)
    {
        $this->middleware('permission:admin panel');
        $this->middleware('permission:show dev_report', ['only' => ['index']]);
        $this->middleware('permission:add dev_report', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit dev_report', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete dev_report', ['only' => ['destroy']]);
        
        $this->reports = $reports;
        $this->tasks = $tasks;
        $this->projects = $projects;
        $this->estimates = $estimates;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user =  User::find(Auth::user()->id);
        $date = $request->get('date') ?? date('Y-m-d');
        
        $reports = $this->reports->all(['user_id' => $user->id, 'date' => $date]);
        
        $type = transValues($this->tasks->getTypes(), false, true);
        
        $resources = $tasks = [];        
        
        if(array_key_first($type) == 'project'){
            $resources = $this->projects->all(true)->sortBy('name')->pluck('name', 'id')->toArray();
        }elseif (array_key_first($type) == 'estimate') {
            $resources = $this->estimates->all(true)->sortBy('name')->pluck('name', 'id')->toArray();
        }
        else{
            $tasks = $this->tasks->all(array_key_first($type))->sortBy('name')->pluck('name', 'id')->toArray();
        }
                
        return view('admin.dev_report.index', compact('reports', 'date', 'user', 'type', 'resources', 'tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('admin.dev_report.create_form');
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
                            'types'  => new CheckResourceTypesRule($this->tasks->getTypes()),
                            'resources' => [
                                                'nullable', 
                                                new CheckResourceRule($request->get('types'))
                                            ],
                            'tasks' => 'required|exists:tasks,id'
                        ];
                        
        $validator = Validator::make($request->all(), $validateRules);        
        if ($validator->fails())
            return response()->json(['error' => $validator->errors()], 422);
           
        $data = $request->only(['date', 'is_done', 'note']);
        $data['user_id'] = Auth::user()->id;
        $data['task_id'] = $request->get('tasks');
        $data['time'] = date('H:i:s', strtotime( intval(($request->get('time_h') ?? '0')) .':'. intval(($request->get('time_m') ?? '0')) ));

        $report = $this->reports->create($data);
        if(!$report)
            return response()->json(['error'=>['form' => __('Error saving form')]], 422);
                
        return response()->json(['success' => __('Report added successfully'), 'report' =>$this->_getJsonResponse($report), 'type' => 'store' ]); 
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
        $report = $this->reports->find($id);
        
        if(!$report)
            return response()->json(['error'=>['report' => __('Record not found')]], 422);
                                
        return view('admin.dev_report.edit_form',compact('report'));
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
        if(!$this->reports->find($id))
            return response()->json(['error'=>['report' => __('Record not found')]], 422);
        
        $data = $request->only([ 'is_done', 'note']);        
        $data['time'] = date('H:i:s', strtotime( intval(($request->get('time_h') ?? '0')) .':'. intval(($request->get('time_m') ?? '0')) ));
        
        $report = $this->reports->update($id, $data);
        if(!$report)
            return response()->json(['error'=>['form' => __('Error saving form')]], 422);
        
        return response()->json(['success' => __('Report updated successfully'), 'report' =>$this->_getJsonResponse($report), 'type' => 'update' ]);         
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!$this->reports->delete($id))
            return response()->json(['error'=>['form' => __('Error delete report')]], 422);
                
        return response()->json(['success' => __('Report deleted successfully')]);
    }
    
    /**
     * Get the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getResourcesList(Request $request)
    {
        $validateRules = [ 'types' => new CheckResourceTypesRule($this->tasks->getTypes())];
        
        $validator = Validator::make($request->all(), $validateRules);        
        if ($validator->fails())
            return response()->json(['error' => $validator->errors()], 422);
                
        
        $resources = $tasks = [];        
        $disabled = true;
        $update = 'resources';
        
        switch ($request->get('types')){
            case 'project':
                $resources = $this->projects->all(true)->sortBy('name')->pluck('name', 'id')->toArray();
                $disabled = false;

                if($request->get('resources')){
                    $tasks = $this->tasks->all($request->get('types'), $request->get('resources'))->sortBy('name')->pluck('name', 'id')->toArray();
                    $update = 'tasks';
                }            
                break;
                
            case 'estimate':
                $resources = $this->estimates->all(true)->sortBy('name')->pluck('name', 'id')->toArray();
                $disabled = false;

                if($request->get('resources')){
                    $tasks = $this->tasks->all($request->get('types'), null)->pluck('name', 'id')
                                        ->union( $this->tasks->all($request->get('types'), $request->get('resources'))->pluck('name', 'id'))
                                        ->toArray();
                    
                    $update = 'tasks';
                }            
                break;
                                
            default :
                $tasks = $this->tasks->all($request->get('types'))->sortBy('name')->pluck('name', 'id')->toArray();
                $update = 'tasks';
                break;
        }
        
        return response()->json(['success' => 'ok', 'resources' => $resources, 'tasks' =>$tasks, 'disabled' => $disabled, 'update' => $update ]);        
    }
    
    private function _getJsonResponse($report)
    {
        $response = new \stdClass;
        
        $response->id = $report->id;
        $response->type = __( ucfirst($report->getTask->type ??  ''));
        $response->resource = $report->getTask->getResourceName()->name ?? '';
        $response->task = $report->getTask->name ??  '';
        $response->is_done = $report->is_done ??  '';
        $response->note = $report->note ??  '';
        $response->time = substr($report->time ??  '', 0, -3);
        
        return $response;
    }
    
}
