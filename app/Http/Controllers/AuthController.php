<?php

namespace App\Http\Controllers;

use App\Http\Resources\userResource;
use App\Http\Resources\userResourceCollection;
use App\Models\User;
use App\Models\users;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();
        //$accessToken=user()->createToken('authToken')->accessToken;
        return response()->json([
            'message' => 'Successful'
        ]);
    }

    public function login(Request $request)
    {
        $login_validation= $request->validate([
            'email'=>'email|required',
            'password'=>'required'
        ]);

        if (!auth()->attempt($login_validation))
        {
            return response(['message'=>'failure']);
        }

        //$accessToken=auth()->user()->createToken('authToken')->accessToken;

        return response([
            'message'=>'Successful',
            'id'=>auth()->id()]);
    }


    //REST
    public function show(User $user):userResource
    {
        return new userResource($user);
    }

    public function index():userResourceCollection
    {
        return new userResourceCollection(user::paginate());

    }

    public function update(User $user,Request $request):userResource
    {
        $user->update($request->all());

        return new userResource($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json();
    }

}
