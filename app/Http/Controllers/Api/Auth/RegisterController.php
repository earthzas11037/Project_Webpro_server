<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function register(Request $request){
        // $request = validate([
        //     'user_id' => 'required',
        //     'password' => 'required',
        //     'name' => 'required',
        //     'tel' => 'required',
        // ]);

        $user = new User([
            // 'user_id' => $request->user_id,
            'password' => bcrypt($request->password),
            'name' => $request->name,
            'tel' => $request->tel,
            'person_id' => $request->person_id,
            'salary' => $request->salary,
            'position_id' => $request->position_id,
            'type_id' => 1
        ]);
        
        $user->save();

        return response()->json(['message' => 'success']);
    }
}
