<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);
        $user = User::where('email', $request->input('email'))->firstOrFail();
        if(Hash::check($request->input('password'), $user->password)){
            return response()->json($user->api_token);

        } else{
            return response()->json('',401);
        }

    }
}    
