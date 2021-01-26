<?php

namespace App\Http\Controllers\addition;

Use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddEmailController extends Controller
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

    
    // View add email page
    public function index() {

        // return view page
        return view('addition.email');
    }
    
    // Update email from database
    public function update(Request $request) {
        
        $validate = $request->validate([
            'email' => ['required', 'string', 'email', 'unique:users'],
        ]);

        if($validate) {
            
            $user = Auth::user();
            $user->email = $request->email;
            $user->save();

            return redirect('/dashboard');
        }
    }
}