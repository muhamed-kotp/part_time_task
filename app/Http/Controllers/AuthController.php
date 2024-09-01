<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ApiResponseHelper;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\RegisterRequest;


class AuthController extends Controller
{
    use ApiResponseHelper;
    public function Register(RegisterRequest $request)
    {
            $user = User::create([
                'name' => $request->name  ,
                'email'=> $request->email ,
                'password'=> Hash::make($request->password),
                'role' => 'user'
            ]);

        $token = $user->createToken("API TOKEN")->plainTextToken;
        $user["token"] = $token;
        return $this->setCode(200)->setMessage('User Created Successfully')->setData($user)->send();
    }//End Method

    public function Login(LoginRequest $request)
    {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password]) )
        {
            $error = $request->header('lang') =="en" ? 'the credintials is not correct' :  'الايميل او الباسورد غير صحيح ' ;
            return response()->json(['status' => false,'message' => $error, 'code' => 401], 401);
        }
        $user = User::where('email',$request->email)->first();
        $token = $user->createToken("API TOKEN")->plainTextToken;

        $user["token"] = $token;


        return $this->setCode(200)->setMessage('User Logeed in Successfully')->setData($user)->send();
    }//End Method


    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return $this->setCode(200)->setMessage('User Logged Out Successfully')->send();
    }//End Method

}
