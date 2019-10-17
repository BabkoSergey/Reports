<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {     
        if ( Auth::check() && !Auth::user()->status){
            Auth::logout();
            $request->session()->flush();
            $request->session()->regenerate();
            
            abort(403, __('Ваш аккаунт заблокован!') );
        }
        if ( Auth::check() && Auth::user()->hasAnyPermission(['admin panel']) ){
            return $next($request);        
        }else if(Auth::check()){
            Auth::logout();
            $request->session()->flush();
            $request->session()->regenerate();
            
            abort(403, __('У Вас нет разрешений!') );
        }
        
        return redirect('/login');
    }
}
