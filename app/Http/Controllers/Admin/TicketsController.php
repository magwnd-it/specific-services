<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use DB;

class TicketsController extends Controller
{
    public function index() {

        $open_tickets = DB::table('tickets') ->orderByDesc('id')->where('status', '=', 1)->get();
        $answered_tickets = DB::table('tickets') ->orderByDesc('id')->where('status', '=', 2)->get();
        $closed_tickets = DB::table('tickets') ->orderByDesc('id')->where('status', '=', 3)->get();
        
        return view('admin.tickets', ['open_tickets'=>$open_tickets,
        'answered_tickets'=>$answered_tickets,'closed_tickets'=>$closed_tickets]);
    }

}