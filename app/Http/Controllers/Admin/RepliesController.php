<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reply;
use Auth;
use DB;

class RepliesController extends Controller
{
    public function store(Request $request) {

        $validate = $request->validate(
            ['replay_body' => ['required', 'string', 'max:1000'], 
             'replay_file' => ['max:2048', 'mimes:jpg,png,jpeg,pdf']]);

        if($request['ticket_id']) {


            $userId = Auth::user()->id;
            
            $reply = new Reply();

            $ticket = DB::table('tickets')
               ->where('id', $request['ticket_id'])
               ->get();

            if($ticket->count() > 0) {

                $notice = 2;
                $status = 2;

                $update = DB::table('tickets')
                        ->where('id', $request['ticket_id'])
                        ->update(['notice' => $notice, 'status' => $status ]);

                if($request->replay_file == null) {

                    $reply->user_id = $userId;
                    $reply->ticket_id = $request['ticket_id'];
                    $reply->replay_body = strip_tags($request['replay_body']);

                }else {

                    $fileName = time() . '.' . $request->replay_file->getclientoriginalextension();

                    $request->replay_file->move(public_path('uploads/replies/') , $fileName);

                    $reply->user_id = $userId;
                    $reply->ticket_id = $request['ticket_id'];
                    $reply->replay_body = strip_tags($request['replay_body']);
                    $reply->replay_file = $fileName;

                    
                }
               
            } else {

                return redirect()->back();

            }

            $reply->save();
            $request->session()->flash('success', 'Your reply has been submitted');
            return redirect()->back();
        }
        
    }
}