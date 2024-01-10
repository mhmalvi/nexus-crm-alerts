<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FollowUp;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class ReminderController extends Controller
{
    public function index()
    {
        try {
            $follow_up = FollowUp::all();
            if ($follow_up) {
                return response()->json([
                    'status' => 200,
                    'message' => "success",
                    'data' => $follow_up
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function broadcast(Request $request)
    {
        // $message = 'You have a meeting at' . ' ' . '3:00';
        $reminder = FollowUp::select('start')->where('user_id', $request->user_id)->where('start', '!=', null)->get();
        // $date2 = Carbon::now()->format('Y-m-d');
        // dd(json_encode($date2));
        // return response()->json([
        //     'message' => 'success',
        //     'data' => $reminder
        // ]);
        // dd(json_encode($reminder));
        $datas = array();
        foreach ($reminder as $data) {
            // $data->toDateTimeString();
            // dd($data);
            $date1 = null;
            $date2 = Carbon::now()->format('Y-m-d');
            $date1 = Carbon::parse($data->start)->format('Y-m-d');
            // dd($date1);
            if ($date2 == $date1) {
                $datas[] = $data;
            }
            // $datas[] = Carbon::parse($data->start)->format('Y-m-d');

        }
        // dd(json_encode($datas));
        $current_time = Carbon::now();
        // dd(json_encode($current_time));
        $earlier_time = Carbon::parse($current_time)->format('H:i');
        // $earlier_time = date('H:i', strtotime($current_time . '-10 minutes'));
        // dd(json_encode($earlier_time));
        $time_array = array();
        if ($datas != []) {
            foreach ($datas as $times) {
                $time = Carbon::parse($times->start)->format('H:i:s');
                $time_array = date('H:i', strtotime($time . '-10 minutes'));
                // dd($earlier_time);
                if ($earlier_time == $time_array) {
                    $reminder_time = $time;
                }
                // if()
            }
        }
        // dd($reminder_time);
        if (isset($reminder_time)) {
            return response()->json([
                'message' => 'success',
                'status' => 200,
                'data' => 'You have a meeting at after 10 minutes at' . ' ' . $time
            ]);
        } else {
            return response()->json([
                'message' => '',
                'status' => 404,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->bearerToken()) {
            $flag = Http::withToken($request->bearerToken())->post('https://crmuser.queleadscrm.com/api/check-if-token-exists');
            $flag_receive = $flag['data'];
            if ($flag_receive == 1) {
                // try {
                $request->validate([
                    'title' => 'required',
                    'start' => 'required',
                    'end' => 'required',
                    'user_id' => 'required',
                    'notification_time' => 'required',
                ]);
                $follow_up = FollowUp::create([
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end,
                    'description' => $request->description,
                    'priority' => $request->priority,
                    'user_id' => $request->user_id,
                    'notification_time' => $request->notification_time,
                    'status' => 1
                ]);
                // dd($follow_up);
                if ($follow_up) {
                    return response()->json([
                        'status' => 201,
                        'message' => "success",
                        'data' => $follow_up
                    ], 201);
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => "failed",
                        'data' => $follow_up
                    ], 500);
                }
                // }
                // } catch (\Throwable $th) {
                //     return response()->json([
                //         'status' => false,
                //         'message' => $th->getMessage()
                //     ], 500);
                // }
            } else {
                return response()->json([
                    'message' => 'Unauthenticated',
                    'status' => 401
                ], 401);
            }
        } else {
            return response()->json([
                'message' => 'Unauthenticated',
                'status' => 401
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if($request->bearerToken()){
                $flag = Http::withToken($request->bearerToken())->post('https://crmuser.queleadscrm.com/api/check-if-token-exists');
                $flag_receive = $flag['data'];
                if($flag_receive == 1){
        $follow_up = FollowUp::where('user_id', $request->user_id)->get();
        if (!$follow_up->isEmpty()) {
            return response()->json([
                'message' => "success",
                "status" => 200,
                "data" => $follow_up
            ]);
        } else {
            return response()->json([
                'message' => "not found",
                "status" => 200,
                'data' => []
            ], 200);
        }
            }else{
                return response()->json([
                        'message' => 'Unauthenticated',
                        'status' => 401
                    ], 401);
            }
        }else{
            return response()->json([
                        'message' => 'Unauthenticated',
                        'status' => 401
                    ], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
        if($request->bearerToken()){
            $flag = Http::withToken($request->bearerToken())->post('https://crmuser.queleadscrm.com/api/check-if-token-exists');
            $flag_receive = $flag['data'];
            if($flag_receive == 1){
        $follow_up = FollowUp::find($id);
        if ($follow_up) {
            $follow_up->update(['title' => $request->title]);
            // $follow_up->start = $request->start;
            // $follow_up->end = $request->end;
            $follow_up->update(['start' => $request->start]);
            $follow_up->update(['end' => $request->end]);
            $follow_up->update(['status' => $request->status]);
            // $follow_up->notification_time = $request->notification_time;
            $follow_up->update(['notification_time' => $request->notification_time]);
            $save = $follow_up->save();
            // $request->start = "";
            // $request->end = "";
            // $request->notification_time = "";
            if ($save) {
                return response()->json([
                    'status' => 201,
                    'message' => "success",
                    'data' => $follow_up
                ], 201);
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => "not found"
            ], 404);
        }
            }else{
                return response()->json([
                        'message' => 'Unauthenticated',
                        'status' => 401
                    ], 401);
            }
        }else{
            return response()->json([
                        'message' => 'Unauthenticated',
                        'status' => 401
                    ], 401);
        }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     $follow_up = FollowUp::find($id);
    //     if ($follow_up) {
    //         $follow_up->status = 0;
    //         $save = $follow_up->save();
    //         if ($save) {
    //             return response()->json([
    //                 'message' => 'deleted',
    //                 'status' => 200,
    //                 'data' => $follow_up
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 'message' => 'not deleted',
    //                 'status' => 500,
    //             ], 500);
    //         }
    //     } else {
    //         return response()->json([
    //             'message' => 'not found',
    //             'status' => 404,
    //         ], 404);
    //     }
    // }

    public function notification_list(Request $request, $id)
    {
        // if($request->bearerToken()){
        //         $flag = Http::withToken($request->bearerToken())->post('https://crmuser.queleadscrm.com/api/check-if-token-exists');
        //         $flag_receive = $flag['data'];
        //         if($flag_receive == 1){
        if ($request->timezone == "Asia/Dhaka") {
            $data = FollowUp::where('user_id', $id)->where('notification_time', '<=', Carbon::now()->addHour(6)->toDateTimeString())->orderBy('id', 'desc')->get();
        } elseif ($request->timezone == "Australia/Sydney") {
            $data = FollowUp::where('user_id', $id)->where('notification_time', '<=', Carbon::now()->addHour(10)->toDateTimeString())->orderBy('id', 'desc')->get();
        }

        if (count($data) > 0) {
            return response()->json([
                'message' => 'success',
                'status' => 200,
                'data' => $data
            ], 200);
        } elseif ($data->isEmpty()) {
            return response()->json([
                'message' => 'No data found',
                'status' => 200,
                'data' => []
            ], 200);
        }
        //     }else{
        //         return response()->json([
        //                 'message' => 'Unauthenticated',
        //                 'status' => 401
        //             ], 401);
        //     }
        // }else{
        //     return response()->json([
        //                 'message' => 'Unauthenticated',
        //                 'status' => 401
        //             ], 401);
        // }

    }

    public function change_status(Request $request, $id)
    {
        if ($request->bearerToken()) {
            $flag = Http::withToken($request->bearerToken())->post('https://crmuser.queleadscrm.com/api/check-if-token-exists');
            $flag_receive = $flag['data'];
            if ($flag_receive == 1) {
                $follow_up = FollowUp::where('id', $id)->where('status', 1)->first();
                if ($follow_up) {
                    $follow_up->status = 0;
                    $data = $follow_up->save();
                    if ($data) {
                        return response()->json([
                            'message' => 'success',
                            'status' => 201,
                            'data' => $follow_up
                        ], 201);
                    } else {
                        return response()->json([
                            'message' => 'failed',
                            'status' => 500
                        ], 500);
                    }
                } else {
                    return response()->json([
                        'message' => 'Already inactive',
                        'status' => 200
                    ], 404);
                }
            } else {
                return response()->json([
                    'message' => 'Unauthenticated',
                    'status' => 401
                ], 401);
            }
        } else {
            return response()->json([
                'message' => 'Unauthenticated',
                'status' => 401
            ], 401);
        }
    }

    public function get_user_details(Request $request)
    {
        // dd($request->user_id);
        $follow_up = FollowUp::where('user_id', $request->user_id)->get();
        // dd(json_encode($follow_up));
        return response()->json([
            'message' => 'success',
            // 'data' => $follow_up
        ]);
    }

    public function destroy($id)
    {
        $follow_up = FollowUp::find($id);
        if ($follow_up) {
            // $follow_up->status = 0;
            $flag = $follow_up->delete();
            // $save = $follow_up->save();
            if ($flag) {
                return response()->json([
                    'message' => 'Deleted successfully',
                    'status' => 201
                ], 201);
            } else {
                return response()->json([
                    'message' => "Failed to delete",
                    'status' => 500,
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'Not found',
                'status' => 404,
            ], 404);
        }
    }
}
