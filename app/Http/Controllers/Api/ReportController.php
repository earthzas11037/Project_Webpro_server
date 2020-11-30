<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Time_attendance;
use App\Models\Report;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ReportController extends Controller
{
    public function getAllReport($year, $month){
        
        $time_attendances = Time_attendance::select('user_id', 'user.name', 'position.position_th', DB::raw('COUNT(DISTINCT(date)) as total_days'), DB::raw('SUM(time_sum) as total_hours'),
                                                    'user.salary', 'user.position_id')
                                            ->join('user', 'user.id', '=', 'time_attendance.user_id')
                                            ->join('position', 'user.position_id', '=', 'position.position_id')
                                            ->whereYear('date', '=', date($year))
                                            ->whereMonth('date', '=', date($month))
                                            ->groupBy('user_id')
                                            ->orderBy('user_id')
                                            ->get();
                                            

        $time_attendances3 = Time_attendance::select('user_id','date', DB::raw('SUM(time_sum) as total_hours'))
                                            ->distinct('date')
                                            ->whereYear('date', '=', date($year))
                                            ->whereMonth('date', '=', date($month))
                                            ->groupBy('user_id', 'date')
                                            ->orderBy('user_id')
                                            ->get();

        $newData2 = [];
        $count = 0;
        if(!empty($time_attendances3[0])){
            $user_target = 0;
            for($i = 0; $i < count($time_attendances3); $i++){
                if($time_attendances3[$i]->user_id == $user_target){
                    $ot = 0;
                    if($time_attendances3[$i]->total_hours > 8){
                        $ot = $time_attendances3[$i]->total_hours - 8;
                    }
                    $newData2[$count-1]->ot = $newData2[$count-1]->ot + $ot;
                }
                else{
                    $user_target = $time_attendances3[$i]->user_id;
                    $ot = 0;
                    if($time_attendances3[$i]->total_hours > 8){
                        $ot = $time_attendances3[$i]->total_hours - 8;
                    }
                    $newData2[$count] = (object) ['user_id' => $time_attendances3[$i]->user_id,
                                                'ot' => $ot];
                    $count++;
                }
            }
        }

        $newData = $time_attendances;
        if(!empty($time_attendances[0])){
            for ($i = 0; $i < count($newData); $i++) {
                if($newData[$i]->position_id == 3){
                    $salary_ot = $newData2[$i]->ot*($newData[$i]->salary*2);
                    $newData[$i] = (object) ['user_id' => $newData[$i]->user_id, 
                                            'name' => $newData[$i]->name,
                                            'position_th' => $newData[$i]->position_th,
                                            'working_days' => $newData[$i]->total_days,
                                            'working_hours' => $newData[$i]->total_hours,
                                            'ot' => $newData2[$i]->ot,
                                            'salary' => $newData[$i]->salary,
                                            'sum_salary' => (($newData[$i]->total_hours-$newData2[$i]->ot)*$newData[$i]->salary)+$salary_ot];
                }
                else{
                    $sum_salary = 0;
                    if($newData[$i]->total_days < 20){
                        $sum_salary = $newData[$i]->salary-((20-$newData[$i]->total_days)*($newData[$i]->salary/20));
                    }
                    else{
                        $sum_salary = $newData[$i]->salary;
                    }
                    $salary_ot = $newData2[$i]->ot*($newData[$i]->salary/20/8);
                    $newData[$i] = (object) ['user_id' => $newData[$i]->user_id, 
                                            'name' => $newData[$i]->name,
                                            'position_th' => $newData[$i]->position_th,
                                            'working_days' => $newData[$i]->total_days,
                                            'working_hours' => $newData[$i]->total_hours,
                                            'ot' => $newData2[$i]->ot,
                                            'salary' => $newData[$i]->salary,
                                            'sum_salary' => $sum_salary+$salary_ot];
                }
            }
        }
        return response()->json(['year' => $year, 'month' => $month, 'newdata' => $newData]);
    }

    public function insert(Request $request){
        $yearmonth = explode("-", $request->date);
        $reports = Report::select('user_id')
                        ->whereYear('date', '=', date($yearmonth[0]))
                        ->whereMonth('date', '=', date($yearmonth[1]))
                        ->first();
        if($reports == null){
            try{
                if(!empty($request->data[0])){
                    $dataRequest = $request->data;
                    $data = [];
                    $data2 = [];
                    for($i = 0; $i < count($request->data); $i++){
                        // $data[$i] = [
                        //     'user_id' => 10000,
                        //     'working_days' => 12,
                        //     'working_hours' => 120,
                        //     'ot' => 5,
                        //     'sum_salary' => 10000,
                        //     'date' => "2020-11-30"
                        // ];
                        $data2[$i] = (array) $request->data[$i];
                    }
                    Report::insert($data2);
                    // return response()->json(['newdata' => $reports]);
                    return response()->json(['message' => 'insert report success']);
                }
                return response()->json(['message' => 'insert report fail']);
    
            }catch (QueryException $e) {
                return response()->json(['message' => 'insert report fail']);
            }
        }
        else{
            return response()->json(['message' => 'insert report fail']);
        }
    }
}
