<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;

use App;
use Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use App\Services\DotEnvEditor;
use App\Services\Settings;

class SettingController extends Controller
{   
    /**
     * * Settings ENV service instance.
     * 
     * @var App\Services\DotEnvEditor
     */
    private $dotEnv;
    
    /**
     * * Settings service instance.
     * 
     * @var App\Services\Settings
     */
    private $settings;

    /**
     * SettingsController constructor.
     *
     * @param Request $request
     * @param Settings $settings
     * @param DotEnvEditor $dotEnv
     */
    public function __construct(DotEnvEditor $dotEnv, Settings $settings)
    {
        $this->middleware('permission:admin panel');
        $this->middleware('permission:show settings', ['only' => ['index']]);
        $this->middleware('permission:edit settings', ['only' => ['update']]);
        
        $this->dotEnv = $dotEnv;
        $this->settings = $settings;
    }
    
    /**
     * Get all application settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = [                        
                        'app' => $this->dotEnv->loadLike('app_'),
                        'mail' => $this->dotEnv->loadLike('mail_'),
                    ];        
                
        return view('admin.settings.index', compact('settings'));
    }
    
    /**
     * Get all application settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function skillsIndex()
    {       
        $settings['skills_cat'] = $this->settings->allLikeNoPrefix('skills_cat', true);    

        return view('admin.settings.index_skills', compact('settings'));
    }
    
     /**
     * Get all application settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function positionsIndex()
    {       
        $settings['positions'] = $this->settings->allLikeNoPrefix('positions', true);    

        return view('admin.settings.index_positions', compact('settings'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $type)
    {
        
        $data = $request->all();        
        unset($data['_method'], $data['_token']);

        switch ($type){            
            case 'app':
            case 'mail':                
                $this->dotEnv->write($data);                
                Artisan::call('config:cache');
                Artisan::call('config:clear');
                break; 
            case 'skills_cat':                
            case 'positions':                
                $this->settings->save($this->_setCodeVal($data, $type.'-'));
                break;                
        }

        Artisan::call('cache:clear');

        if(in_array($type, ['app', 'mail'])){
            $hash = $this->_setHash($type);                        
            return redirect(route('settings.index').$hash);        
        }
        
        return response()->json(['success'=>'ok']);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function destroy($code)
    {
        $this->settings->removeByCode($code);
        
        return response()->json(['success'=>'ok']);
    }
    
    private function _setCodeVal($data, $prefix = '')
    {
        $keydData = [];
        foreach ($data['code'] as $key => $code) {
            $keydData[$prefix.$code] = $data['val'][$key];
        }
                
        return $keydData;
    }
    
    private function _setHash($codeType)
    {
        switch ($codeType){            
            default :
                $hash = '#'.$codeType;
        }
        
        return $hash;
    }
        
}
