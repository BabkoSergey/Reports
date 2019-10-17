<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Project;
use App\Estimate;

class CheckResourceRule implements Rule
{
    private $type;
    
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        switch ($this->type){
            case 'project':
                if(Project::find($value)) return true;                
                break;
            case 'estimate':
                if(Estimate::find($value)) return true;                
                break;
                
            default :
                return true;
                break;
        }        
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Resource does not exist!');
    }
}
