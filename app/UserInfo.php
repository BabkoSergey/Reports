<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserInfo extends Authenticatable
{

    public $table = "user_infos";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [        
        'birthday', 'address', 'gender', 'marital', 'children', 'education', 'courses', 'languages', 'user_id'
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
        'education' => 'json',
        'courses'   => 'json',
        'languages' => 'json'
    ];
    
    /**
     * Get the user's birthday.
     *
     * @param  string  $birthday
     * @return string
     */
    public function getBirthdayAttribute($value)
    {
        return $value ? date('Y-m-d', strtotime($value)) : null;
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
