<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'phone_number' => 'required',
            'dob' => 'required',
            'role' => 'required',
            'email' => 'required|string|unique:users,email',
            'password' => 'required',
        ]);

        $user = User::create([
            'first_name' => $request['first_name'],
            'middle_name' => $request['middle_name'],
            'last_name' => $request['last_name'],
            'gender' => $request['gender'],
            'phone_number' => $request['phone_number'],
            'dob' => $request['dob'],
            'role' => $request['role'],
            'status' => 1,
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'phone_number' => 'required',
            'dob' => 'required',
            'role' => 'required',
            // 'email' => 'required|string|unique:users,email',
        ]);

        User::where('id', $id)->update([
            'first_name' => $request['first_name'],
            'middle_name' => $request['middle_name'],
            'last_name' => $request['last_name'],
            'gender' => $request['gender'],
            'phone_number' => $request['phone_number'],
            'dob' => $request['dob'],
            'role' => $request['role'],
            // 'email' => $request['email'],
            'status' => $request['status'],
        ]);

        $user = User::find($id);
        return response()->json($user, 200);
    }

    public function activateUser($id)
    {
        $user = User::find($id);
        $user->status = 1;
        $user->save();
        return response()->json(['success' => 'User activated successfully'], 200);
    }

    public function deactivateUser($id)
    {
        $user = User::find($id);
        $user->status = 0;
        $user->save();
        return response()->json(['success' => 'User deactivated successfully'], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(DB::table("users")->where('id',$id)->delete()){
            $user = DB::table('users')->get();
            return response()->json($user, 200);
        }else{
            return 500;
        }
    }
}
