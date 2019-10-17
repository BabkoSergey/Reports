<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DevReport extends Model
{
    public $table = "dev_reports";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [        
        'task_id', 'user_id', 'date', 'time', 'is_done', 'note', 'resourse'
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
    public function getTask()
    {                
        return $this->hasOne('App\Task', 'id', 'task_id');
    }
    
    
    /**
     * One to one relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getDeveloper ()
    {                
        return $this->hasMany('App\User','user_id', 'id');       
    }
    
    /**
     * One to one relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getResource()
    {   
        switch ($this->getTask->type ?? null){
            case 'project':                
                return Project::find($this->resourse);         
                break;
            
            case 'estimate':
                return Estimate::find($this->resourse);         
                break;
                
            default :
                return 'null';
                break;
        }
        
    }
}
