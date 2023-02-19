<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();

        // Check password
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Incorrect username or password.'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        if($user->status == "1") {
            $response = [
                'user' => $user,
                'token' => $token,
            ];
        } else {
            return response(['message' => 'Account deactivated. Please contact administrator.'], 401);
        }

        return response($response);
    }

    public function logout ()
    {
        if(isset(Auth::user()->id)){
            Auth::user()->tokens()->where('id', Auth::user()->currentAccessToken()->id)->delete();
            return response()->json([
                'status' => true
            ], 200);
        }else{
            return response()->json([
                'status' => false
            ], 401);
        }
    }

    public function changePassword(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'current_password' => 'required',
            'new_password' => 'required',
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();

        // Check password
        if(!$user || !Hash::check($fields['current_password'], $user->password)) {
            return response([
                'message' => 'Incorrect current password.'
            ], 401);
        }

        $user->password = Hash::make($fields['new_password']);
        $user->save();

        $response = [
            $user,
        ];
        return response("Password updated successfully", 200);
    }

 
}
