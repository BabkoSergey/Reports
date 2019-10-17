<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use Hash;
use App;
use Illuminate\Support\Facades\Auth;
use Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Config;
use Languages;

use App\Services\FilesStorage;
use App\Services\Settings;

use App\Repository\UserInfosRepository;
use App\Repository\UserSkillsRepository;
use App\User;

class UserController extends Controller
{    
    /**
     * FilesStorage service instance.
     *
     * @var App\Services\FilesStorage;
     */
    private $filesStorage; 
    
    private $userInfos;
    private $usersSkills;
    
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
    public function __construct(FilesStorage $filesStorage, UserInfosRepository $userInfos, UserSkillsRepository $usersSkills, Settings $settings)
    {
        $this->middleware('permission:admin panel');
        $this->middleware('permission:show users', ['only' => ['index', 'show', 'usersDTAjax']]);
        $this->middleware('permission:add users', ['only' => ['create','store']]);        
        $this->middleware('permission:edit users', ['only' => ['edit','update', 'ban']]);
        $this->middleware('permission:delete users', ['only' => ['destroy']]);   
        
        $this->filesStorage = $filesStorage;
        $this->userInfos = $userInfos;
        $this->usersSkills = $usersSkills;
        $this->settings = $settings;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $roles = $this->_avalRoles();
        
        return view('admin.users.create',compact('roles'));
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
                'name' => 'required|unique:users,name',
                'email' => 'nullable|email|unique:users,email',
                'password' => 'required|min:6|same:confirm-password',            
        ];
         
        $input = $request->all();
        $validator = Validator::make($input, $validateRules);
        
        if ($validator->fails())
            return redirect()->back()
                        ->withErrors($validator)->withInput();
                
        $input['password'] = Hash::make($input['password']);
                
        $user = User::create($input);
                
        $roles = $this->_unsetAnavalRoles($request->input('roles') ?? []);
        
        $user->assignRole($roles); 
        
        return redirect()->route('users.edit', ['id'=>$user->id])
                        ->with('success', __('User added successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {        
        $user = User::where('id',$id)->first();
        
        if(!$user)
            return redirect()->route('users.index')
                        ->with('errores', [__('User is not found!')]);
        
        $roles = function($roles) {             
            foreach($roles as $i=>$role)
                $roles[$i] = __($role);
            
            return $roles->toArray();            
        };        
        $user->setroles = $roles($user->getRoleNames());
        
        if($user->logo)
            $user->logo = $this->filesStorage->getImageUrl('avatars', $user->logo );
                
        $settings = [            
            'skills_cat'    => $this->settings->allLikeNoPrefix('skills_cat', true)
        ]; 
        
        return view('admin.users.show',compact('user', 'settings'));
    }
    
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profileShow()
    {        
        $user = User::find(Auth::user()->id);
        
        if(!$user)
            return redirect()->back()
                        ->with('errores', [__('User is not found!')]);
                
        $roles = $this->_avalRoles();        
        
        if($user->logo)
            $user->avatar = $this->filesStorage->getImageUrl('avatars', $user->logo );
        
        $userInfo = $this->userInfos->findByUser($user->id);
        $userInfo->enums = [
            'gender' => transValues($this->userInfos->getEnums('gender'), false, true),
            'marital' => transValues($this->userInfos->getEnums('marital'), false, true),
            'langLevels' => transValues(Config::get('lengLevels.levels')),
            'langNotes' => transValues(Config::get('lengLevels.notes')),
            'langs' => Languages::lookup(null, App::getLocale()),
        ];
        
        $settings = [
            'skillLevels'   => transValues(Config::get('skillLevels.levels')),
            'skillNotes'    => transValues(Config::get('skillLevels.notes')),
            'skills_cat'    => $this->settings->allLikeNoPrefix('skills_cat', true),
            'skills'        => $this->usersSkills->getSkillsList()
        ]; 
        
        $user->skills = $user->getSkills->groupBy('cat');
        
        return view('admin.users.profile',compact('user', 'roles', 'userInfo', 'settings' ));
        
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        
        if(!$user)
            return redirect()->route('users.index')
                        ->with('errores', [__('User is not found!')]);
                
        $roles = $this->_avalRoles();        
        
        if($user->logo)
            $user->avatar = $this->filesStorage->getImageUrl('avatars', $user->logo );
        
        $userInfo = $this->userInfos->findByUser($user->id);
        $userInfo->enums = [
            'gender'        => transValues($this->userInfos->getEnums('gender'), false, true),
            'marital'       => transValues($this->userInfos->getEnums('marital'), false, true),
            'langLevels'    => transValues(Config::get('lengLevels.levels')),
            'langNotes'     => transValues(Config::get('lengLevels.notes')),
            'langs'         => Languages::lookup(null, App::getLocale()),            
        ];
        
        $settings = [
            'skillLevels'   => transValues(Config::get('skillLevels.levels')),
            'skillNotes'    => transValues(Config::get('skillLevels.notes')),
            'skills_cat'    => $this->settings->allLikeNoPrefix('skills_cat', true),
            'skills'        => $this->usersSkills->getSkillsList()
        ]; 
        
        $user->skills = $user->getSkills->groupBy('cat');
                        
        return view('admin.users.edit',compact('user', 'roles', 'userInfo', 'settings' ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $user = User::find($id);        
        
        if(!$user)
            return response()->json(['message' => __('Transmitted data is incorrect.'), 'errors'=>['model'=>[__('Transmitted data is incorrect.')]]], 422);
        
        if(!Auth::user()->hasRole('SuperAdmin') && $user->hasRole('SuperAdmin') )
            return response()->json(['message' => __('You do not have rights to change this data!'), 'errors'=>['user'=>[__('You do not have rights to change this data!')]]], 422);                

        if(!Auth::user()->hasAnyRole(['Admin', 'SuperAdmin']) && $user->hasRole('Admin') )
            return response()->json(['message' => __('You do not have rights to change this data!'), 'errors'=>['user'=>[__('You do not have rights to change this data!')]]], 422);
        
        $validateRules = [];        
        $input = $request->all();
                    
        if($request->get('name') && $user->name != $request->get('name'))
            $validateRules['name'] = 'required|unique:users,name';
        
        if($request->get('email') && $user->email != $request->get('email'))
            $validateRules['email'] = 'required|email|unique:users,email';            
        
        if($request->get('password')){  
            $validateRules['password'] = 'required|min:6|same:confirm-password';
            $input['password'] = Hash::make($input['password']);
        }
        
        $this->validate($request, $validateRules);
                        
        $user->update($input);        
        
        if(!$request->input('password')){            
            $user->syncRoles($request->input('roles')); 
        }
        
        return response()->json(['success'=>'ok']);

    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userInfoUpdate(Request $request, $id)
    {       
        $userInfo = $this->userInfos->find($id);
        
        if(!$userInfo)
            return response()->json(['message' => __('Transmitted data is incorrect.'), 'errors'=>['model'=>[__('Transmitted data is incorrect.')]]], 422);
        
        if(!Auth::user()->hasRole('SuperAdmin') && $userInfo->getUser->hasRole('SuperAdmin') )
            return response()->json(['message' => __('You do not have rights to change this data!'), 'errors'=>['user'=>[__('You do not have rights to change this data!')]]], 422);                

        if(!Auth::user()->hasAnyRole(['Admin', 'SuperAdmin']) && $userInfo->getUser->hasRole('Admin') )
            return response()->json(['message' => __('You do not have rights to change this data!'), 'errors'=>['user'=>[__('You do not have rights to change this data!')]]], 422);
        
        if(!$this->userInfos->update($userInfo, $request->all()))
            return response()->json(['message' => __('Error saving form'), 'errors'=>['model'=>[__('Error saving form')]]], 422);
        
        return response()->json(['success'=>'ok']);

    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userSkillUpdate(Request $request, $id)
    {       
        $user = User::find($id);        
        
        if(!$user)
            return response()->json(['message' => __('Transmitted data is incorrect.'), 'errors'=>['model'=>[__('Transmitted data is incorrect.')]]], 422);
        
        if(!Auth::user()->hasRole('SuperAdmin') && $user->hasRole('SuperAdmin') )
            return response()->json(['message' => __('You do not have rights to change this data!'), 'errors'=>['user'=>[__('You do not have rights to change this data!')]]], 422);                

        if(!Auth::user()->hasAnyRole(['Admin', 'SuperAdmin']) && $user->hasRole('Admin') )
            return response()->json(['message' => __('You do not have rights to change this data!'), 'errors'=>['user'=>[__('You do not have rights to change this data!')]]], 422);
        
        $updatedSkills = $this->usersSkills->allUpdate($user, $request->all());
        if(!$updatedSkills)
            return response()->json(['message' => __('Error saving form'), 'errors'=>['model'=>[__('Error saving form')]]], 422);
        
        return response()->json(['success'=>'ok', 'skills' => $updatedSkills]);

    }
        
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function profileInfoUpdate(Request $request)
    {
        
        $user = User::find(Auth::user()->id);
        
        if(!$user)
            return response()->json(['message' => __('Transmitted data is incorrect.'), 'errors'=>['model'=>[__('Transmitted data is incorrect.')]]], 422);
        
        $validateRules = [];        
        $input = $request->all();
        
        if($request->get('email') && $user->email != $request->get('email'))
            $validateRules['email'] = 'required|email|unique:users,email';            
        
        $this->validate($request, $validateRules);
                        
        $user->update($input);        
                
        return response()->json(['success'=>'ok']);

    }
       
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function profilePasswordUpdate(Request $request)
    {
        
        $user = User::find(Auth::user()->id);
        
        if(!$user)
            return response()->json(['message' => __('Transmitted data is incorrect.'), 'errors'=>['model'=>[__('Transmitted data is incorrect.')]]], 422);
                
        $validateRules = [];        
        $input = $request->all();
        
        if($request->get('password')){  
            $validateRules['password'] = 'required|min:6|same:confirm-password';
            $input['password'] = Hash::make($input['password']);
        }
        
        $this->validate($request, $validateRules);
                        
        $user->update($input);        
        
        return response()->json(['success'=>'ok']);

    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userIDs = explode(',', $id);
        
        $validator = Validator::make($userIDs,['numeric']);
        $success = '';
        
        foreach($userIDs as $userID){
            $user = User::find($userID);
            if($user && !Auth::user()->hasRole('SuperAdmin') && $user->hasRole('SuperAdmin') ){
                $validator->errors()->add($user->name, __('You do not have rights to change this data!').$user->name.'!');
                continue;
            }
            
            if($user && !Auth::user()->hasAnyRole(['Admin', 'SuperAdmin']) && $user->hasRole('Admin') ){
                $validator->errors()->add($user->name, __('You do not have rights to change this data!').$user->name.'!');
                continue;
            }
            
            $success .= ($success != '' ? __('User').':' : '') .' '.$user->name;
            
            $user->delete();
        }
        
        $success .= $success != '' ? ' '.__('deleted').'!' : '';
        
        return redirect()->route('users.index')
                        ->with('success',$success)->withErrors($validator);
    }
    
    /**
     * Ban the specified resource from storage.
     *
     * @param  str  $ids
     * @return \Illuminate\Http\Response
     */
    public function ban(Request $request, $ids)
    {
        $banIDs = explode(',', $ids);
        $statuses = [];
        
        $type = $request->get('action') ? ($request->get('action') == 'hold' ? false : true ) : null;
                
        foreach($banIDs as $userID){
            $user = User::find($userID);            
            
            if($user){
                
                if($user && !Auth::user()->hasRole('SuperAdmin') && $user->hasRole('SuperAdmin') )
                    continue;

                if($user && !Auth::user()->hasAnyRole(['Admin', 'SuperAdmin']) && $user->hasRole('Admin') )
                    continue;
                
                $user->status = $type ?? $user->status ? false : true;
                $user->save();
                $statuses[$userID] = $user->status;
            }
            
        }
        
        return response()->json(['success'=>'ok', 'statuses'=>$statuses]);
    }
    
    /**
     * Datatable Ajax fetch
     *
     * @return
     */
    public function usersDTAjax(Request $request) {

        if($request->get('role')){
            $roleName = $request->get('role');
            $users = User::whereHas("roles", function($q) use ($roleName){ $q->where("name", $roleName); })->get();
        }else{
            $users = User::all();
        }
                            
        $out = datatables()->of($users)
                ->editColumn('logo', function($users) {                    
                    return $users->logo ? $this->filesStorage->getImageUrl('avatars', $users->logo ) : $users->logo;
                })                
                ->editColumn('position', function($users) {                    
                    return $users->getPositionName();
                })                
                ->addColumn('roles', function($users) {
                    $roles = $users->roles->pluck('name', 'id')->all();
                    foreach($roles as $i=>$role){
                        $roles[$i] = __($role);
                    }
                    return implode(', ', $roles);
                })                
                ->addColumn('actions', '')
                ->toJson();

        return $out;
    }    
    
    private function _avalRoles(){
        
        $roles = $this->_unsetAnavalRoles(Role::pluck('name', 'name')->all());
                
        foreach($roles as $i=>$role)
            $roles[$i] = __($role);
        
        return $roles;
    }
    
    private function _unsetAnavalRoles($roles, $key=true){
                
        if( !Auth::user()->hasRole('SuperAdmin') ){
            if($key && array_key_exists('SuperAdmin', $roles)){
                unset($roles['SuperAdmin']);
            }else{
                unset($roles[array_search('SuperAdmin', $roles)]);
            }            
        }
                    
        if( !Auth::user()->hasAnyRole(['Admin', 'SuperAdmin']) ){
            if($key && array_key_exists('Admin', $roles)){
                unset($roles['Admin']);
            }else{
                unset($roles[array_search('Admin', $roles)]);
            }            
        }
                
        return $roles;
    }    
    
}
