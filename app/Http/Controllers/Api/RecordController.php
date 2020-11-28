<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Time_attendance;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;

class RecordController extends Controller
{
    public function getAllRecord(){
        
        $time_attendances = Time_attendance::all();

        return response()->json(['data' => $time_attendances]);
    }

    public function recordTime(Request $request){
        $id = $request->user_id ;
        $time_attendance = Time_attendance::where('user_id','=',$id)->orderBy('seq', 'DESC')->limit(1)->first();
        
        if($time_attendance != null){
            if($time_attendance->time_in != null && $time_attendance->time_out == null){
                $seq = $time_attendance->seq;
                $time = Carbon::now()->toTimeString();
                Time_attendance::where('seq', '=',$seq)->update([
                    'time_out' => $time,
                ]);
                return response()->json(['data' => "update record success"]);
            }
            else{
                $date = Carbon::now()->toDateString();
                $time = Carbon::now()->toTimeString();
                Time_attendance::create([
                    'user_id' => $id,
                    'date' => $date,
                    'time_in' => $time,
                ]);
                return response()->json(['data' => "insert record success"]);
            }
        }
        else{
            $date = Carbon::now()->toDateString();
            $time = Carbon::now()->toTimeString();
            Time_attendance::create([
                'user_id' => $id,
                'date' => $date,
                'time_in' => $time,
            ]);
            return response()->json(['data' => "insert record success"]);
        }
        return response()->json(['data' => $time_attendance->time_out]);
    }

    public function comeIn(Request $request){
        $id = $request->user_id ;
        $time_attendance = Time_attendance::where('user_id','=',$id)->orderBy('seq', 'DESC')->limit(1)->first();
        if($time_attendance != null){
            if($time_attendance->time_in != null && $time_attendance->time_out == null){
                return response()->json(['data' => "insert record fail"]);
            }
            else{
                $date = Carbon::now()->toDateString();
                $time = Carbon::now()->toTimeString();
                Time_attendance::create([
                    'user_id' => $id,
                    'date' => $date,
                    'time_in' => $time,
                ]);
                return response()->json(['data' => "insert record success"]);
            }
        }
        else{
            $date = Carbon::now()->toDateString();
            $time = Carbon::now()->toTimeString();
            Time_attendance::create([
                'user_id' => $id,
                'date' => $date,
                'time_in' => $time,
            ]);
            return response()->json(['data' => "insert record success"]);
        }
    }

    public function getOut(Request $request){
        $id = $request->user_id ;
        $time_attendance = Time_attendance::where('user_id','=',$id)->orderBy('seq', 'DESC')->limit(1)->first();
        if($time_attendance != null){
            if($time_attendance->time_in != null && $time_attendance->time_out == null){
                $seq = $time_attendance->seq;
                $time = Carbon::now()->toTimeString();
                Time_attendance::where('seq', '=',$seq)->update([
                    'time_out' => $time,
                ]);
                return response()->json(['data' => "update record success"]);
            }
            else{
                return response()->json(['data' => "update record fail"]);
            }
        }
        else{
            return response()->json(['data' => "update record fail"]);
        }
    }
}
