<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsApiAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user()->role;
            if($user =="admin"){
                return $next($request);
            }
            else{
                $error =App::getLocale() =='en'?'Sorry This for admin only' : 'غير مسموح ... هذا للأدمن فقط' ;
                return response()->json([
                    'status' => false,
                    'message' => $error,
                ],401);
            }
    }
    }
