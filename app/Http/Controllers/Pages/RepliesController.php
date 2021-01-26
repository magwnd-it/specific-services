<?php

namespace App\Http\Controllers\Pages;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Reply;

class RepliesController extends Controller
{
    public function addRepleyData(Request $request) {
        
        $validate = $request->validate(
            ['replay_body' => ['required', 'string', 'max:1000'], 
             'replay_file' => ['max:2048', 'mimes:jpg,png,jpeg,pdf']]);

        if($request['ticket_id']) {

            $userId = Auth::user()->id;
            $reply = new Reply();

            $ticket = DB::table('tickets')
               ->where('id', $request['ticket_id'])
               ->where('user_id', $userId)
               ->get();

            if($ticket->count() > 0) {

                $status = 1;

                $update = DB::table('tickets')
                        ->where('id', $request['ticket_id'])
                        ->update(['status' => $status ]);

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