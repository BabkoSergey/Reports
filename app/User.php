<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Traits\HasRoles;

use App\Services\Settings;
use App\Services\FilesStorage;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [        
        'name', 'email', 'password', 'phone', 'first_name', 'last_name', 'patron_name', 'logo', 'status', 'description', 'delete'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'boolean',
        'pos_status' => 'boolean',
    ];
    
    /**
     * One to one relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getInfo()
    {                
        return $this->hasOne('App\UserInfo', 'user_id', 'id');
    }
    
    /**
     * One to Many relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getPositions ()
    {                
        return $this->hasMany('App\UserPosition','user_id', 'id');       
    }
    
    /**
     * One to Many relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getPositionName ()
    {           
        $positions = $this->getPositions()->get()->sortBy('from');
        
        $settings = new Settings();
        
        $lastPosition = $positions->where('from', '<=', date('Y-m-d H:i:s', strtotime(date('Y-m-d', time()))) )->last();
        if($lastPosition && (!$lastPosition->to || $lastPosition->to == date('Y-m-d H:i:s', strtotime(date('Y-m-d', time()))) ) )
            return $settings->get('positions-'.$lastPosition->position, '');

        $firstFuturePosition = $positions->first();
        if($firstFuturePosition)
            return $settings->get('positions-'.$firstFuturePosition->position, '');
        
        return $settings->get('positions-'.($lastPosition->position ?? ''), '');       
    }
    
    /**
     * One to Many relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getSkills ()
    {                
        return $this->hasMany('App\UserSkill','user_id', 'id');       
    }
    
    /**
     * One to Many relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getExperiences ()
    {                
        return $this->hasMany('App\UserExperience','user_id', 'id');       
    }
    
    /**
     * One to Many relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getDevReports ()
    {                
        return $this->hasMany('App\DevReport','user_id', 'id');       
    }
    
    /**
    * Get the user Full name short.
    *
    * @return string
    */
    public function getFullName()
    {        
        return $this->last_name ? ($this->last_name .' '. ($this->first_name ? $this->first_name.' '.($this->patron_name ? $this->patron_name: '') : '')) : $this->name;        
    }
    
    /**
    * Get the user Full name short.
    *
    * @return string
    */
    public function getFullNameAbr()
    {        
        return $this->last_name ? (mb_substr($this->first_name, 0, 1) . ($this->first_name ? mb_substr($this->first_name, 0, 1).($this->patron_name ? mb_substr($this->patron_name, 0, 1) : '') : '')) : mb_substr($this->name, 0, 1);
    }
    
    /**
    * Get the user Full name short.
    *
    * @return string
    */
    public function getShortFullName()
    {        
        return $this->last_name ? ($this->last_name .' '. ($this->first_name ? mb_substr($this->first_name, 0, 1).'.'.($this->patron_name ? mb_substr($this->patron_name, 0, 1).'.' : '') : '')) : $this->name;        
    }
    
    /**
    * Get the user Avatar URL.
    *
    * @return string
    */
    public function getUserAvatar()
    {
        if(!$this->logo)
            return asset('/img/default-user.png'); 
        
        $filesStorage = new FilesStorage();
        
        return $filesStorage->getImageUrl('avatars', $this->logo) ?? asset('/img/default-user.png');
         
    }
    
    /**
     * One to Many relationship with type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
