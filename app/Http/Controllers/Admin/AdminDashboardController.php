<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    
    public function index() {
        
    
        $total_tickets = DB::table('tickets')
                           ->get();

        $open_tickets = DB::table('tickets')
                          ->where([['status', '=', 1]])
                          ->get();

        $answered_tickets = DB::table('tickets')
                              ->where([['status', '=', 2]])
                              ->get();

        $closed_tickets = DB::table('tickets')
                            ->where([['status', '=', 3]])
                            ->get();

        return view('admin.dashboard', ['total_tickets'=>$total_tickets, 'open_tickets'=>$open_tickets, 
        'answered_tickets'=>$answered_tickets, 'closed_tickets'=>$closed_tickets]);
    }
    
    
    public function RedirectToDashboard() {
        return redirect('/admin/dashboard');
    }
}