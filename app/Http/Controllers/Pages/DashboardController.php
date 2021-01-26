<?php

namespace App\Http\Controllers\Pages;

use DB;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
    */
    public function index()
    {
        
        // Get user id
        $userId = Auth::user()->id;

        // Answered tickets count
        $answered_tickets_count = DB::table('tickets')
                            ->where([['user_id', '=', $userId], ['status', '=', 2]])
                            ->get();

        // Opened tickets count
        $open_tickets_count = DB::table('tickets')
                            ->where([['user_id', '=', $userId], ['status', '=', 1]])
                            ->get();
        
        // Get opened tickets data
        $open_tickets = Ticket::orderBy('id', 'DESC')->where([
                                ['user_id', '=', $userId],
                                ['status', '=', 1],
                                ])->limit(6)->get();

        // Get answered tickets data
        $answered_tickets = Ticket::orderBy('id', 'DESC')->where([
                                ['user_id', '=', $userId],
                                ['status', '=', 2],
                                ])->limit(6)->get();     

        // Get closed tickets data                        
        $closed_tickets = Ticket::orderBy('id', 'DESC')->where([
                                ['user_id', '=', $userId],
                                ['status', '=', 3],
                                ])->get();                        
                        

        // Return all data
        return view('pages.dashboard', ['open_tickets'=>$open_tickets, 
                                      'answered_tickets'=>$answered_tickets,
                                       'closed_tickets'=>$closed_tickets,
                                       'answered_tickets_count' => $answered_tickets_count,
                                       'open_tickets_count' => $open_tickets_count]);
    }
}