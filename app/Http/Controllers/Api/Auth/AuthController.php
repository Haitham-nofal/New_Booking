<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $request->validate([
            "name"=>"required|string|max:225",
            "email"=> "required|email|unique:users,email",
            "password"=>"required|min:8"
        ]);

        $hashPassword=Hash::make($request->password);

        $user=User::create([
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>$hashPassword,
        ]);


        $token=$user->createToken("ApiToken")->plainTextToken;

        return response()->json([
            "success"=>true,
            "message"=> "user created successfully",
            "token"=>$token,
        ],201);


    }
    public function login(LoginRequest $request)
    {
        $request->validated();

        $user=User::where("email",$request->email)->first();

        if(!$user || !Hash::check($request->password,$user->password))
            {
                return response()->json([
                    "success"=>false,
                    "message"=>"Invalid email or password",
                ],403);
            }

            $user=User::where("email",$request->email)->first();
            $token=$user->createToken("ApiToken")->plainTextToken;

            return response()->json([
                "success"=>true,
                "token"=>$token,
                "user"=> new UserResource($user),
            ],200);

    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
               return response()->json([
                    "success"=>true,
                    "message"=>"user logout successfully",
                ],200);
    }



}
