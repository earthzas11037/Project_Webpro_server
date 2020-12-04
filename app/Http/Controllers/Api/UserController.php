<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function getAllUsers(){
        
        $users = User::select('user.id', 'user.name', 'user.tel', 'user.person_id', 'user.salary', 
                            'user.position_id', 'position.position_eng', 'position.position_th', 
                            'user.type_id', 'type.type_name')
                        ->join('position', 'user.position_id', '=', 'position.position_id')
                        ->join('type', 'user.type_id', '=', 'type.type_id')
                        ->where('user.type_id','=', 1)
                        ->get();

        return response()->json(['data' => $users]);
    }

    public function getUserById($id){
        $users = User::select('user.id', 'user.name', 'user.tel', 'user.person_id', 'user.salary', 
                            'user.position_id', 'position.position_eng', 'position.position_th', 
                            'user.type_id', 'type.type_name')
                        ->join('position', 'user.position_id', '=', 'position.position_id')
                        ->join('type', 'user.type_id', '=', 'type.type_id')
                        ->where('id','=',$id)
                        ->first();
        
        return response()->json(['data' => $users]);
    }

    public function update(Request $request){
        try{
            User::where('id', '=', $request->id)->update([
                'name' => $request->name,
                'tel' => $request->tel,
                'person_id' => $request->person_id,
                'position_id' => $request->position_id,
                'salary' => $request->salary,
            ]);
    
            return response()->json(['message' => 'update user success']);

        }catch (QueryException $e) {
            return response()->json(['data' => "update user fail"]);
        }
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
