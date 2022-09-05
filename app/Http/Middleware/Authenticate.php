<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Exception;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        
        // if ($request->is('api') || $request->is('api/*')) {
        //     return redirect()->guest('/login');
        // try{
       // if (!$request->is('admin')) {
            //return response()->json(['status'=>'error','code'=>'401', 'msg'=>'You  are not authorised']);

           // return view('unauthorised');
            //return route('');
        //}
        //return redirect()->guest(route('login'));

        // try{
        //    if (!$request->is('admin')) {
            
            
        //     return response()->json(['status'=>'error','code'=>'401', 'msg'=>'You  are not authorised']);

            
        // }
        // }
        // catch(\Exception $e){
        //   return response()->json(['status'=>'error','code'=>'401', 'msg'=>'You  are not authorised']);
        // }
        if (! $request->expectsJson()) {
            return route('login');
        }

        
    }
}
