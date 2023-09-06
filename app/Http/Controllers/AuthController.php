<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function registrasi(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
 
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $saveData = new User();
        $saveData->name='user';
        $saveData ->email=$request->email;
        $saveData->password=Hash::make($request->password);
        $saveData->save();

        return response()->json( [
            'status'   => 201,
            'message'  => 'success',
            'id_user'  =>$saveData->id
        ] );
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $emailCheck= User::where('email', $request->email)->first();
            $credentials = $request->only('email', 'password');
                if (!$token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
        if($emailCheck){
            $passwordCheck = Hash::check($request->password, $emailCheck->password);
            if($passwordCheck){
                
                return response()->json([
                    'status'  => 200,
                    'message' =>'success',
                    'type_token' =>'Bearer Token',
                    'token'   =>  $token,
                    'data'   =>$emailCheck
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' =>'Password tidak sesuai'
                ]);
            }
        }else{
            return response()->json([
                'status' => 404,
                'message' =>'Email tidak ditemukan'
            ]);
        }

    }
}
