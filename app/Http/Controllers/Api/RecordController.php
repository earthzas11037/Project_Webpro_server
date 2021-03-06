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
        
        $time_attendances = Time_attendance::select('time_attendance.seq', 'time_attendance.date', 'user.id', 'user.name', 'position.position_th', 'time_attendance.time_in', 'time_attendance.time_out', 'time_attendance.time_sum')
                                            ->join('user', 'user.id', '=', 'time_attendance.user_id')
                                            ->join('position', 'user.position_id', '=', 'position.position_id')
                                            ->orderBy('time_attendance.date', 'DESC')
                                            ->get();

        return response()->json(['data' => $time_attendances]);
    }

    public function getAllById($id){
        
        $time_attendances = Time_attendance::where('user_id','=', $id)->get();

        return response()->json(['data' => $time_attendances]);
    }

    public function getAllRecordByDate($start, $end){
        
        $time_attendances = Time_attendance::select('time_attendance.seq', 'time_attendance.date','user.id', 'user.name', 'position.position_th', 'time_attendance.time_in', 'time_attendance.time_out', 'time_attendance.time_sum')
                                            ->join('user', 'user.id', '=', 'time_attendance.user_id')
                                            ->join('position', 'user.position_id', '=', 'position.position_id')
                                            ->whereBetween('date', [$start, $end])
                                            ->orderBy('time_attendance.seq', 'DESC')
                                            ->get();

        return response()->json(['data' => $time_attendances]);
    }

    public function update(Request $request){
        $startTime = Carbon::parse($request->time_in);
        $endTime = Carbon::parse($request->time_out);
        $totalDuration =  $startTime->diff($endTime)->format('%H');

        try{
            Time_attendance::where('seq', '=', $request->seq)->update([
                'time_in' => $request->time_in,
                'time_out' => $request->time_out,
                'time_sum' => $totalDuration,
            ]);
    
            return response()->json(['message' => 'update record success']);

        }catch (QueryException $e) {
            return response()->json(['data' => "update record fail"], 400);
        }

    }

    public function recordTime(Request $request){
        $id = $request->user_id ;
        
        try{
            $time_attendance = Time_attendance::where('user_id','=',$id)->orderBy('seq', 'DESC')->limit(1)->first();
            if($time_attendance != null){
                if($time_attendance->time_in != null && $time_attendance->time_out == null){
                    $seq = $time_attendance->seq;
                    $time = Carbon::now()->toTimeString();
                    $startTime = Carbon::parse($time_attendance->time_in);
                    $endTime = Carbon::parse($time);
    
                    $totalDuration =  $startTime->diff($endTime)->format('%H');
                    // $totalDuration =  $startTime->diff($endTime)->format('%H:%I:%S');
                    Time_attendance::where('seq', '=',$seq)->update([
                        'time_out' => $time,
                        'time_sum' => $totalDuration,
                    ]);
                    return response()->json(['message' => "บันทึกเวลาออก => วันที่ : ".$time_attendance->date." เวลา : ".$time]);
                }
                else{
                    $date = Carbon::now()->toDateString();
                    $time = Carbon::now()->toTimeString();
                    Time_attendance::create([
                        'user_id' => $id,
                        'date' => $date,
                        'time_in' => $time,
                    ]);
                    return response()->json(['message' => "บันทึกเวลาเข้า => วันที่ : ".$date." เวลา : ".$time]);
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
                return response()->json(['message' => "บันทึกเวลาเข้า => วันที่ : ".$date." เวลา : ".$time]);
            }
            return response()->json(['message' => $time_attendance->time_out]);

        }catch (QueryException $e) {
            return response()->json(['message' => "insert record fail"], 400);
        }
    }

    public function comeIn(Request $request){
        $id = $request->user_id ;

        try{
            $time_attendance = Time_attendance::where('user_id','=',$id)->orderBy('seq', 'DESC')->limit(1)->first();
            if($time_attendance != null){
                if($time_attendance->time_in != null && $time_attendance->time_out == null){
                    return response()->json(['data' => "insert record fail"], 400);
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
        }catch (QueryException $e) {
            return response()->json(['data' => "insert record fail"], 400);
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
                return response()->json(['data' => "update record fail"], 400);
            }
        }
        else{
            return response()->json(['data' => "update record fail"], 400);
        }
    }
}
