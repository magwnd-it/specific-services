<?php
namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Auth;
use DB;
use Illuminate\Http\Request;

class TicketsController extends Controller
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

    public function TicketsData()
    {

        $userId = Auth::user()->id;

        $all_tickets_count = DB::table('tickets')
            ->where([['user_id', '=', $userId]])
            ->get();

        $answered_tickets_count = DB::table('tickets')
            ->where([['user_id', '=', $userId], ['status', '=', 2]])
            ->get();

        $open_tickets_count = DB::table('tickets')
            ->where([['user_id', '=', $userId], ['status', '=', 1]])
            ->get();

        $closed_tickets_count = DB::table('tickets')
            ->where([['user_id', '=', $userId], ['status', '=', 3]])
            ->get();

        $all_tickets = Ticket::withCount('replies')
            ->orderByDesc('id')
            ->where('user_id', $userId)
            ->paginate(10, ['*'], 'all');

        $open_tickets = Ticket::withCount('replies')
            ->orderByDesc('id')
            ->where([['user_id', '=', $userId], ['status', '=', 1]])
            ->paginate(10, ['*'], 'opened');

        $answered_tickets = Ticket::withCount('replies')
            ->orderByDesc('id')
            ->where([['user_id', '=', $userId], ['status', '=', 2]])
            ->paginate(10, ['*'], 'answered');

        $closed_tickets = Ticket::withCount('replies')
            ->orderByDesc('id')
            ->where([['user_id', '=', $userId], ['status', '=', 3]])
            ->paginate(10, ['*'], 'closed');

        return view('pages.tickets',
            ['open_tickets' => $open_tickets, 'answered_tickets' => $answered_tickets,
                'closed_tickets' => $closed_tickets, 'all_tickets' => $all_tickets,
                'all_tickets_count' => $all_tickets_count, 'answered_tickets_count' => $answered_tickets_count,
                'open_tickets_count' => $open_tickets_count, 'closed_tickets_count' => $closed_tickets_count]);
    }

    public function backTotickets()
    {
        return redirect()->back();
    }

    public function search(Request $request)
    {
        $userId = Auth::user()->id;
        $q = $request->input('q');
        if ($q) {
            $all_tickets = Ticket::where([['user_id', '=', $userId], ['subject', 'LIKE', '%' . $q . '%']])
                ->orWhere([['user_id', '=', $userId], ['id', 'like', '%' . $q . '%']])
                ->orWhere([['user_id', '=', $userId], ['product', 'like', '%' . $q . '%']])
                ->withCount('replies')
                ->orderbyDesc('id')
                ->get();
            return view('pages.search', ['all_tickets' => $all_tickets]);
        } else {
            return redirect('/tickets');
        }

    }
}
