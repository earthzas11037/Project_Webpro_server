<?php

namespace App\Http\Controllers\Api;

use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave_record;
use Carbon\Carbon;
class LeaveRecordController extends Controller
{
    public function getAllLeaveRecord(){
        
        $leave_records = Leave_record::all();

        return response()->json(['data' => $leave_records]);
    }

    public function insert(Request $request){
        
        try{
            Leave_record::create([
                'user_id' => $request->user_id,
                'date_start' => $request->date_start,
                'date_end' => $request->date_end,
                'leave_type' => $request->leave_type,
                'detail' => $request->detail,
                'status_id' => 1,
            ]);

            return response()->json(['message' => 'success']);

        }catch (QueryException $e) {
            return response()->json(['message' => 'fail']);
        }
    }

    public function approve(Request $request){
        
        $seq = $request->seq;
        
        if($seq != null){
            $leave_record = Leave_record::where('seq','=',$seq)->first();
            if($leave_record != null ){
                if($leave_record->status_id == 1){
                    Leave_record::where('seq', '=',$seq)->update([
                        'status_id' => 2,
                    ]);
                    return response()->json(['message' => 'update success']);
                }
                else{
                    return response()->json(['message' => 'update fail']);
                }
            }
            else{
                return response()->json(['message' => 'update fail']);
            }
        }

        return response()->json(['message' => 'update fail']);
    }

    public function disapprove(Request $request){
        
        $seq = $request->seq;

        if($seq != null){
            $leave_record = Leave_record::where('seq','=',$seq)->first();
            if($leave_record != null ){
                if($leave_record->status_id == 1){
                    Leave_record::where('seq', '=',$seq)->update([
                        'status_id' => 3,
                    ]);
                    return response()->json(['message' => 'update success']);
                }
                else{
                    return response()->json(['message' => 'update fail']);
                }
            }
            else{
                return response()->json(['message' => 'update fail']);
            }
        }

        return response()->json(['message' => 'update fail']);
    }
}
