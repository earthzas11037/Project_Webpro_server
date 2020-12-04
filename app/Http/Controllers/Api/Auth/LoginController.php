<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use JWTAuth;

class LoginController extends Controller
{
    public function login(Request $request){

        $input = $request->only(['id', 'password']);
        $token = null;

        $user = User::select('user.name', 'position.position_eng', 'position.position_th', 'type.type_name')
                        ->join('position', 'user.position_id', '=', 'position.position_id')
                        ->join('type', 'user.type_id', '=', 'type.type_id')
                        ->where('id','=',$request->id)
                        ->first();

        $customClaims = ['name' => $user->name, 'position_eng' => $user->position_eng, 'position_th' => $user->position_th, 'type' => $user->type_name];

        if (!$token = JWTAuth::claims($customClaims)->attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }

    public function checkTokenAuthen()
    {
        return response()->json([
            'message' => true
        ]);
    }
}
