<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function getAllUsers(){
        
        $users = User::all();

        return response()->json(['data' => $users]);
    }

    public function getUserById($id){
        $users = User::where('id','=',$id)->get();
        
        return response()->json(['data' => $users]);
    }

    // decode JWT Token (เอา payload ออกมา)
    public function checkToken(Request $request){
        try {
            // attempt to verify the credentials and create a token for the user
            $token = JWTAuth::getToken();
            $apy = JWTAuth::getPayload($token);
            // ดึงค่าจากตัวแปร type
            $type = $apy->get('type');

            return response()->json(['data' => $apy]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
    
            return response()->json(['token_expired'], 500);
    
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
    
            return response()->json(['token_invalid'], 500);
    
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
    
            return response()->json(['token_absent' => $e->getMessage()], 500);
    
        }
    }
}
