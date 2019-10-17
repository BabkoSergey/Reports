<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;

use App\Repository\TasksRepository;

class TaskController extends Controller
{
    private $tasks;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TasksRepository $tasks)
    {
        $this->middleware('permission:admin panel');
        $this->middleware('permission:show tasks', ['only' => ['index', 'show']]);
        $this->middleware('permission:add tasks|append resources', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit tasks', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete tasks', ['only' => ['destroy']]);
        
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
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $resourse = $request->get('resourse') ?? null;
        $type = $request->get('type') ?? transValues($this->tasks->getTypes(), false, true);
                                
        return view('admin.task.create_form', compact('resourse', 'type'));
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
                'name' => 'required',
                'type' => Rule::in($this->tasks->getTypes())
            ];
        
        $input = $request->all();
        $validator = Validator::make($input, $validateRules);
        
        if ($validator->fails())
            return response()->json(['error' => $validator->errors()], 422);
        
        $input['user_id'] = Auth::user()->id;
        $input['add_type'] = 'plan';
        
        $task = $this->tasks->create($input);
        
        if(!$task)
            return response()->json(['error'=>['form' => __('Error saving form')]], 422);
           
        return response()->json(['success' => __('Task added successfully'), 'task' => $task, 'type' => 'store', 'resource' => 'task']);
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
        $task = $this->tasks->find($id);
        
        if(!$task)
            return response()->json(['error'=>['task' => __('Record not found')]], 422);
                                
        return view('admin.task.edit_form',compact('task'));
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
        if(!$this->tasks->find($id))
            return response()->json(['error'=>['task' => __('Record not found')]], 422);
           
        $validateRules = [                
                'name' => 'required',
            ];          
        
        $input = $request->all();
        $validator = Validator::make($input, $validateRules);
        
        if ($validator->fails())
            return response()->json(['error' => $validator->errors()], 422);
        
        $input['user_id'] = Auth::user()->id;
        $input['add_type'] = 'plan';
        
        $task = $this->tasks->update($id, $input);
        if(!$task)
            return response()->json(['error'=>['form' => __('Error saving form')]], 422);
        
        return response()->json(['success' => __('Task updated successfully'), 'task' => $task, 'type' => 'update']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!$this->tasks->delete($id))
            return response()->json(['error'=>['form' => __('Error delete task')]], 422);
                
        return response()->json(['success' => __('Task deleted successfully')]);
    }
}
