<?php

namespace App\Http\Controllers\addition;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddPasswordController extends Controller
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
    
    // View add password page
    public function index() {

        // return view page
        return view('addition.password');
    }
    
    // Update password from database
    public function update(Request $request) {
        
        $validate = $request->validate( [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if($validate) {
            $user = Auth::user();
            $user->password = bcrypt($request->get('password'));
            $user->save();

            return redirect('/dashboard');
        }
    }
}