<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Services\Settings;

class UserPosition extends Authenticatable
{

    public $table = "user_positions";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [        
        'user_id', 'from', 'to', 'position'
    ];
            
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        
    ];
            
    /**
     * One to one relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getUser()
    {                
        return $this->hasOne('App\User', 'id', 'user_id');
    }
        
    public function getPositionName ()
    {   
        $settings = new Settings();
                
        return $settings->get('positions-'.($this->position ?? ''), '');       
    }
}
