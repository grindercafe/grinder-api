<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifyApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $api_key = $request->header('Authorization');
        
        if(!$api_key) {
            return response()->json([
                'status_code'=> 401,
                'message'=> 'You Must Provide an Api Key!'
            ], 401);
        }
        
        $is_valid = DB::table('api_keys')->where('key', $api_key)->exists();

        if(!$is_valid) {
            return response()->json([
                'status_code'=> 401,
                'message'=> 'Invalid Api Key!'
            ], 401);
        }

        return $next($request);
    }
}
