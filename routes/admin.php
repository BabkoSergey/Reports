<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => false, 'register' => false, 'reset' => false]);

Route::group(['prefix' => 'files', 'middleware' => ['auth', 'admin'], 'namespace' => 'Files'], function () {
        
    Route::get('get_all', ['as'=>'image.list.get','uses'=>'ImageController@getUploadImgs']);
    Route::post('upload', ['as'=>'image.upload.post','uses'=>'ImageController@imageUploadPost']);
    Route::post('uploads', ['as'=>'image.upload.multi.post','uses'=>'ImageController@imageUploadMultiPost']);
    Route::post('remove', ['as'=>'image.remove.post','uses'=>'ImageController@imageDeletePost']);

});

Route::group(['prefix' => '', 'middleware' => ['auth', 'admin'], 'namespace' => 'Admin'], function () {
        
    /*
     * Dashboard
     */
    Route::get('','DashboardController@index');
    Route::get('dashboard','DashboardController@index');  
    Route::get('messages', 'DashboardController@fetchMessages');
    Route::post('messages', 'DashboardController@sendMessage');
    
    /*
     * Chat
     */    
    Route::get('messages', 'ChatController@fetchMessages');
    Route::post('messages', 'ChatController@sendMessage');
        
    /*
    * Resource
    */
    Route::resource('resource','ResourceController');
    
    /*
    * Project
    */
    Route::resource('projects','ProjectController');
    Route::get('projects_dt_ajax', 'ProjectController@projectsDTAjax');       
    
    /*
    * Estimate
    */
    Route::resource('estimates','EstimateController');
    Route::get('estimates_dt_ajax', 'EstimateController@estimatesDTAjax');       
    Route::patch('/estimates/update/view/{id}', ['as'=>'estimates.update.view-status','uses'=>'EstimateController@updateViewStatus']);        
    Route::post('/estimates/update/timing/{id}', ['as'=>'estimates.update.timing','uses'=>'EstimateController@updateTiming']);        
    Route::get('/estimates/pdf/{id}', ['as'=>'estimates.pdf','uses'=>'EstimateController@createPDF']);
    Route::get('/estimates/xls/{id}', ['as'=>'estimates.xls','uses'=>'EstimateController@createXLS']);
    
    /*
    * Task
    */
    Route::resource('tasks','TaskController');
    
    /*
    * Developer Report
    */
    Route::resource('dev_reports','DevReportController');
    Route::post('dev_reports/get_resources_list', ['as'=>'dev_reports.get_resources_list','uses'=>'DevReportController@getResourcesList']);    
    
    /*
     * Users
     */
    Route::resource('users','UserController');    
    Route::get('users_dt_ajax', 'UserController@usersDTAjax');    
    Route::get('/users/{ids}/ban','UserController@ban');
    Route::patch('/users/info/{id}/update', ['as'=>'user_info.update','uses'=>'UserController@userInfoUpdate']);     
    Route::patch('/users/skill/{id}/update', ['as'=>'user_skill.update','uses'=>'UserController@userSkillUpdate']);         
    //Profile
    Route::get('/users/profile/show', ['as'=>'users.profile','uses'=>'UserController@profileShow']);        
    Route::patch('/users/profile/update/info', ['as'=>'profile.info.update','uses'=>'UserController@profileInfoUpdate']);        
    Route::patch('/users/profile/update/password', ['as'=>'profile.password.update','uses'=>'UserController@profilePasswordUpdate']);  
    //Position
    Route::resource('positions', 'PositionController');
    Route::post('/positions/{user_id}/lay_off', ['as'=>'positions.lay_off','uses'=>'PositionController@layOffUser']);  
    Route::post('/positions/{user_id}/lay_on', ['as'=>'positions.lay_on','uses'=>'PositionController@layOnUser']);  
    
    /*
     * Permissions
     */
    Route::resource('permissions','PermissionController');
    Route::get('permissions_dt_ajax', 'PermissionController@permissionsDTAjax');    
    
    /*
     * Roles
     */
    Route::resource('roles','RoleController');
    Route::get('roles_dt_ajax', 'RoleController@rolesDTAjax');        
    
    /*
     * Reports Block
     */
    
    Route::group(['prefix' => 'reports'], function () {
        
        /*
         * Reports
         */
        Route::get('dev_reports', ['as'=>'reports.dev.index','uses'=>'ReportController@devIndex']);        
        Route::get('dev_report/{id}', 'ReportController@devDayReportsShow');
    });
    
    /*
     * Settings Block
     */
    Route::group(['prefix' => 'settings'], function () {
        
        /*
        * Settings
        */
        Route::resource('settings', 'SettingController');        
        Route::get('skills', ['as'=>'settings.skills.index','uses'=>'SettingController@skillsIndex']);
        Route::get('positions', ['as'=>'settings.positions.index','uses'=>'SettingController@positionsIndex']);        
        /*
        * Translate
        */
        Route::get('translate', ['as'=>'settings.translate.index','uses'=>'TranslateController@index']);
        Route::post('translations/update', 'TranslateController@transUpdate')->name('translation.update.json');
        Route::post('translations/updateKey', 'TranslateController@transUpdateKey')->name('translation.update.json.key');
        Route::delete('translations/destroy/{key}', 'TranslateController@destroy')->name('translations.destroy');
        Route::post('translations/create', 'TranslateController@store')->name('translations.create');
                
    });
            
});
