<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;

use App\Services\Settings;

use App\Repository\UserPositionsRepository;

class PositionController extends Controller
{
    private $positions;
    
    /**
     * * Settings service instance.
     * 
     * @var App\Services\Settings
     */
    private $settings;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserPositionsRepository $positions, Settings $settings)
    {
        $this->middleware('permission:admin panel'); 
        
        $this->positions = $positions;
        $this->settings = $settings;
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
        $validateRules = [ 
                            'user_id' => 'required|exists:users,id'
                        ];
                        
        $validator = Validator::make($request->all(), $validateRules);        
        if ($validator->fails())
            return response()->json(['error' => $validator->errors()], 422);
        
        $params = [
                    'user_id'   => $request->get('user_id'),
                    'positions' => $this->settings->allLikeNoPrefix('positions', true)
                ];
        
        return view('admin.users.create_form.create_form_position', compact('params'));
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
                            'user_id'   => 'required|exists:users,id',
                            'position'  => 'required',
                            'from'      => 'required|date_format:Y-m-d'
                        ];
                        
        $validator = Validator::make($request->all(), $validateRules);        
        if ($validator->fails())
            return response()->json(['error' => $validator->errors()], 422);
        
        $position = $this->positions->create($request->all());
        if(!$position || $position->error){
            return response()->json(['error'=>['form' => __($position->error)]], 422);
        }
        
        $user = $this->positions->getUser($position->id);
        $position->refresh();
        
        return response()->json([
                                    'success'       => __('Added successfully'),
                                    'userPosition'  => [
                                                        'pos_status'    => $user->pos_status,
                                                        'pos_name'      => $user->getPositionName()
                                                    ],
                                    'action'        => 'create',
                                    'positionBefore'=> $this->_getPositionIDBefore($position),
                                    'position'      => $this->_getPositionActions($position),                                    
                            ]);        
    }

    public function layOffUser($user_id)
    {
        $position = $this->positions->layOffUser($user_id);     
        
        if(!$position){
            return response()->json(['error'=>['position' => __('Error')]], 422);
        }
        
        $user = $this->positions->getUser($position->id);
        
        return response()->json([
                                    'success'       => __('Success'),
                                    'userPosition'  => [
                                                        'pos_status'    => $user->pos_status,
                                                        'pos_name'      => $user->getPositionName()
                                                    ],
                                    'position'      => $this->_getPositionActions($position),                                    
                            ]);
    }    
    
    public function layOnUser($user_id)
    {
        $position = $this->positions->layOnUser($user_id);     
        if(!$position){
            return response()->json(['error'=>['position' => __('Error')]], 422);
        }
        
        $user = $this->positions->getUser($position->id);
        
        return response()->json([
                                    'success'       => __('Success'),
                                    'userPosition'  => [
                                                        'pos_status'    => $user->pos_status,
                                                        'pos_name'      => $user->getPositionName()
                                                    ],
                                    'position'      => $this->_getPositionActions($position),                                    
                            ]);
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
        $position = $this->positions->find($id);     
        if(!$position)
            return response()->json(['error'=>['position' => __('Transmitted data is incorrect.')]], 422);
        
        $params = [                    
                    'positions' => $this->settings->allLikeNoPrefix('positions', true)
                ];
        
        return view('admin.users.edit_form.edit_form_positions', compact( 'position', 'params'));
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
        $validateRules = [                             
                            'position'  => 'required',
                            'from'      => 'required|date_format:Y-m-d'
                        ];
                        
        $validator = Validator::make($request->all(), $validateRules);        
        if ($validator->fails())
            return response()->json(['error' => $validator->errors()], 422);
        
        $position = $this->positions->update($request->all());
        if(!$position || $position->error){
            return response()->json(['error'=>['form' => __($position->error)]], 422);
        }
        
        $user = $this->positions->getUser($position->id);
        $position->refresh();
        
        return response()->json([
                                    'success'       => __('Updated successfully'),
                                    'userPosition'  => [
                                                        'pos_status'    => $user->pos_status,
                                                        'pos_name'      => $user->getPositionName()
                                                    ],
                                    'position'      => $this->_getPositionActions($position),                                    
                            ]); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->positions->getUser($id);
        if(!$user)
            return response()->json(['error'=>['form' => __('Error delete')]], 422);
        
        if(!$this->positions->delete($id))
            return response()->json(['error'=>['form' => __('Error delete')]], 422);
        
        $user->refresh();
                
        return response()->json([
                                    'success' => __('Deleted successfully'),
                                    'userPosition' => [
                                                        'pos_status'    => $user->pos_status,
                                                        'pos_name'      => $user->getPositionName()
                                                    ],
                                    'position'      => null
                            ]); 
    }
    
    private function _getPositionIDBefore($position)
    {
        if(!$position)
            return null;
                                
        return $this->positions->getPositionIDBefore($position);
    }
    
    private function _getPositionActions($position)
    {
        if(!$position)
            return null;
                          
        $position->from = $position->from ? date('Y-m-d', strtotime($position->from)) : '';
        $position->to = $position->to ? date('Y-m-d', strtotime($position->to)) : '';
        $position->position = $position->getPositionName();
        
        $actions = ['delete'  => false, 'edit'    => false, 'lay_on'  => false];
        $user = $this->positions->getUser($position->id);
        
        if(Auth::user()->hasPermissionTo('office positions edit')){
            $actions['delete'] = strtotime($position->from) >= strtotime(date('Y-m-d', time())) ? true : false;
            $actions['edit'] = strtotime($position->from) >= strtotime(date('Y-m-d', time())) ? true : false;
            $actions['lay_on'] = strtotime($position->from) == strtotime(date('Y-m-d', time())) && $user && !$user->pos_status ? true : false;
        }
        
        $position->actions = $actions;
        
        return $position;
    }
    
}
