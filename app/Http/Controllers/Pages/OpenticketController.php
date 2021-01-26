<?php
namespace App\Http\Controllers\Pages;

use Auth;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ticket;

class OpenticketController extends Controller
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

    public function index()
    {

        $products = DB::table('products')->orderByDesc('id')
            ->get();

        return view('pages.open-ticket', ['products' => $products]);

    }

    public function InsertTicket(Request $request)
    {

        $validate = $request->validate([
            'subject' => ['required', 'string', 'max:70'],
            'product' => ['required', 'string'],
            'priority' => ['required', 'string'],
            'description' => ['required', 'string', 'max:1000'],
            'attachfile' => ['max:2048', 'mimes:jpg,png,jpeg,pdf'],
            'terms' => ['required'],
        ]);

        if ($validate)
        {

            $product = DB::table('products')->where([['product_name', '=', $request['product']]])->get();
            if ($product->count() > 0)
            {

                $userId = Auth::user()->id;
                $ticket = new Ticket();

                if ($request->attachfile == null)
                {

                    $ticket->user_id = $userId;
                    $ticket->subject = strip_tags($request['subject']);
                    $ticket->product = $request['product'];
                    $ticket->priority = $request['priority'];
                    $ticket->description = strip_tags($request['description']);

                }
                else
                {

                    $fileName = time() . '.' . $request
                        ->attachfile
                        ->getclientoriginalextension();

                    $request
                        ->attachfile
                        ->move(public_path('uploads/tickets/') , $fileName);

                    $ticket->user_id = $userId;
                    $ticket->subject = strip_tags($request['subject']);
                    $ticket->product = $request['product'];
                    $ticket->priority = $request['priority'];
                    $ticket->description = strip_tags($request['description']);
                    $ticket->attachfile = $fileName;

                }

                $ticket->save();
                return redirect()->route('ticket', $ticket);

            }
            else
            {
                return redirect()
                    ->back();
            }
        }
    }

}