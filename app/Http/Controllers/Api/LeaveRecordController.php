<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\CalendarController;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Leave_record;
use Carbon\Carbon;
class LeaveRecordController extends Controller
{
    public function getAllLeaveRecord(){
        
        $leave_records = Leave_record::all();

        return response()->json(['data' => $leave_records]);
    }

    public function getAllWaitForApproval(){
        
        $leave_records = Leave_record::select('leave_record.seq', 'leave_record.user_id', 'user.name',
                                                'leave_record.date_start', 'leave_record.date_end', 'leave_record.leave_type', 'leave_record.detail')
                                        ->join('user', 'user.id', '=', 'leave_record.user_id')
                                        ->where('status_id','=', '1')
                                        ->get();

        return response()->json(['data' => $leave_records]);
    }

    public function getAllById($id){
        
        $leave_records1 = Leave_record::where('user_id','=', $id)
                                        ->where('status_id','=', '1')
                                        ->get();

        $leave_records2 = Leave_record::where('user_id','=', $id)
                                        ->where('status_id','=', '2')
                                        ->get();

        $leave_records3 = Leave_record::where('user_id','=', $id)
                                        ->where('status_id','=', '3')
                                        ->get();

        return response()->json(['datawait' => $leave_records1, 'dataapprove' => $leave_records2, 'datadisapprove' => $leave_records3]);
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
            return response()->json(['message' => 'fail'], 400);
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

                    $obj = new CalendarController;
                    $result = $obj->insertAll($leave_record);
                    if($result == 'success'){
                        return response()->json(['message' => 'update success']);
                    }
                    else{
                        return response()->json(['message' => 'insert calendar fail'], 400);
                    }
                }
                else{
                    return response()->json(['message' => 'update fail'], 400);
                }
            }
            else{
                return response()->json(['message' => 'update fail'], 400);
            }
        }

        return response()->json(['message' => 'update fail'], 400);
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
                    return response()->json(['message' => 'update fail'], 400);
                }
            }
            else{
                return response()->json(['message' => 'update fail'], 400);
            }
        }

        return response()->json(['message' => 'update fail'], 400);
    }
}
