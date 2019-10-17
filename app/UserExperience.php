<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserExperience extends Model
{    
    public $table = "user_experiences";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [        
        'project', 'from', 'to', 'role', 'team', 'description', 'technologies', 'user_id'
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
     * Get the from.
     *
     * @param  string  $from
     * @return string
     */
    public function getFromAttribute($value)
    {
        return $value ? date('Y M', strtotime($value)) : null;
    }
    
    /**
     * Get the to.
     *
     * @param  string  $to
     * @return string
     */
    public function getToAttribute($value)
    {
        return $value ? date('Y M', strtotime($value)) : null;
    }
    
    /**
     * One to one relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getUser()
    {                
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
