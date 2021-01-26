<?php

namespace App\Http\Controllers\Pages;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Reply;

class ViewticketController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function ViewTicketData($id) {

        $userId = Auth::user()->id;

        $ticket = DB::table('tickets') 
                     ->where([['user_id', '=', $userId]])
                     ->find($id);

 
        $replies = Reply::where('ticket_id',$id)->with('user')->get();

        $update = DB::table('tickets')
                        ->where('id', $id)
                        ->where('user_id', $userId)
                        ->update(['notice' => 1]);

        
        if ($ticket === null) {

            return redirect()->back();

        }else {
            
             return view('pages.ticket', ['ticket' => $ticket, 'replies'=>$replies]);
        }       

       
    }

    public function UpdateTicketData(Request $request) {

        $userId = Auth::user()->id;

        if($request['ticket_id']) {
            
            $status = 3;
            
            $update = DB::table('tickets')
               ->where('id', $request['ticket_id'])
               ->where('user_id', $userId)
               ->update(['status' => $status]);

        }elseif($request['ticketId']) {

            $status = 1;

            $update = DB::table('tickets')
               ->where('id', $request['ticketId'])
               ->where('user_id', $userId)
               ->update(['status' => $status]);
               
        }else {

            $request->session()->flash('error', 'it looks like there is a problem');
            return redirect()->back();
        }

        if($update) {

            return redirect()->back();

        }else {

            $request->session()->flash('error', 'it looks like there is a problem');
            return redirect()->back();
            
        }     
        
    }
}