<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserSkill extends Authenticatable
{

    public $table = "user_skills";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [        
        'cat', 'name', 'level', 'exp', 'used', 'user_id'
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
     * Get the used year.
     *
     * @param  string  $used
     * @return string
     */
    public function getUsedAttribute($value)
    {
        return $value ? date('Y', strtotime($value)) : null;
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
