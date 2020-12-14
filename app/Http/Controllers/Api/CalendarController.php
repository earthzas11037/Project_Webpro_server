<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Calendar;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function getAllCalendar(){
        
        $calendars = Calendar::all();

        return response()->json(['data' => $calendars]);
    }

    public function insert(Request $request){
        
        try{
            Calendar::create([
                'user_id' => $request->user_id,
                'topic' => $request->title,
                'detail' => $request->detail,
                'date_start' => $request->start,
                'date_end' => $request->end,
                'calendar_type' => 'USER',
            ]);

            return response()->json(['message' => 'success']);

        }catch (QueryException $e) {
            return response()->json(['message' => 'fail'], 400);
        }
    }

    public function remove($seq){
        
        try{
            Calendar::where("seq", "=" , $seq)->delete();

            return response()->json(['message' => 'success']);

        }catch (QueryException $e) {
            return response()->json(['message' => 'fail'], 400);
        }
    }

    public function update(Request $request){
        
        try{
            Calendar::where('seq', '=', $request->seq)->update([
                'topic' => $request->title,
                'detail' => $request->detail,
                'date_start' => $request->start,
                'date_end' => $request->end,
            ]);

            return response()->json(['message' => 'success']);

        }catch (QueryException $e) {
            return response()->json(['message' => 'fail'], 400);
        }
    }

    public function insertAll($leave_record){
        
        try{
            $user = User::select('name')->where('id','=',$leave_record->user_id)->first();

            Calendar::create([
                'user_id' => $leave_record->user_id,
                'topic' => $user->name . " " . $leave_record->leave_type,
                'detail' => $leave_record->detail,
                'date_start' => $leave_record->date_start,
                'date_end' => $leave_record->date_end,
                'calendar_type' => 'ALL',
            ]);

            return 'success';

        }catch (QueryException $e) {
            return 'fail';
        }
    }

    public function getAllById($id){
        
        $calendars = Calendar::select('seq', 'user_id', 'topic as title', 'detail', 'date_start as start', 'date_end as end', 'calendar_type')
                                ->where('user_id','=',$id)
                                ->orWhere('calendar_type', 'ALL')->get();

        return response()->json(['data' => $calendars]);
    }
}
