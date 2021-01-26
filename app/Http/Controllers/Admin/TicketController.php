<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Reply;
use App\Models\User;
use DB;

class TicketController extends Controller
{
    public function index($id) {

        $data = Ticket::find($id);

        $replies = Reply::where('ticket_id',$id)->with('user')->get();

        if($data) {

            return view('admin.ticket', ['data' =>  $data, 'replies'=>$replies]);

        }else {
            return redirect()->back();
        }
    }

    public function BackToTickets() {
         return redirect('/admin/tickets');
    }

    public function update(Request $request) {

        if($request['ticket_id']) {
            
            $status = 3;
            
            $update = DB::table('tickets')
               ->where('id', $request['ticket_id'])
               ->update(['status' => $status]);

        }elseif($request['ticketId']) {

            $status = 1;

            $update = DB::table('tickets')
               ->where('id', $request['ticketId'])
               ->update(['status' => $status]);
               
        }else {

            return redirect()->back();
        }

        if($update) {

            return redirect()->back();

        }else {
            
            return redirect()->back();
            
        }     
        
    }
    
}