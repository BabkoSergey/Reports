<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Project;
use App\Estimate;

class Task extends Model
{
    public $table = "tasks";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [        
        'type', 'resourse', 'name', 'todo', 'note', 'user_id', 'add_type'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [        
        //
    ];
    
    /**
     * One to one relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getProject()
    {                
        return $this->hasOne('App\Project', 'id', 'resourse');
    }
    
    /**
     * One to one relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getResourceName()
    {                
        switch ($this->type){
            case 'project':                
                return Project::find($this->resourse);         
                break;
            
            case 'estimate':                
                return Estimate::find($this->resourse);         
                break;
                
            default :
                return null;
                break;
        }        
    }
    
    
    /**
     * One to Many relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getDevReports ()
    {                
        return $this->hasMany('App\DevReport','task_id', 'id');       
    }
}
