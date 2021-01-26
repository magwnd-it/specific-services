<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
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
        return view('pages.edit-profile');
    }

    public function update(Request $request)
    {

        $validate = null;

        if (Auth::user()->email === $request['email']) {

            $validate = $request->validate([
                'firstname' => ['required', 'alpha_dash', 'string', 'max:255'],
                'lastname' => ['required', 'alpha_dash', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'avatar' => ['max:2048', 'mimes:jpg,png,jpeg'],
            ]);

        } else {

            $validate = $request->validate([
                'firstname' => ['required', 'alpha_dash', 'string', 'max:255'],
                'lastname' => ['required', 'alpha_dash', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'avatar' => ['max:2048', 'mimes:jpg,png,jpeg'],
            ]);
        }

        if ($validate) {
            $userId = Auth::user()->id;
            if ($request['avatar'] == null) {
                $update = DB::table('users')
                    ->where('id', $userId)
                    ->update(['firstname' => $request['firstname'],
                        'lastname' => $request['lastname'],
                        'email' => $request['email']]);
                if ($update) {
                    $request->session()->flash('success', 'Your information has been updated successfully');
                    return redirect()->back();
                } else {
                    return redirect()->back();
                }
            } else {
                $avatar = Auth::user()->avatar;
                if ($avatar == "default-avatar.png") {
                    $avatarName = md5(time()) . '.' . $request->avatar->getclientoriginalextension();
                    $request->avatar->move('cdn/user/', $avatarName);
                    $update = DB::table('users')
                        ->where('id', $userId)
                        ->update(['firstname' => $request['firstname'],
                            'lastname' => $request['lastname'],
                            'email' => $request['email'],
                            'avatar' => $avatarName]);
                    if ($update) {
                        $request->session()->flash('success', 'Your information has been updated successfully');
                        return redirect()->back();
                    } else {
                        return redirect()->back();
                    }
                } else {
                    if (file_exists('cdn/user/' . $avatar)) {
                        $deleteOldAvatar = File::delete('cdn/user/' . $avatar);
                    }
                    $avatarName = md5(time()) . '.' . $request->avatar->getclientoriginalextension();
                    $request->avatar->move('cdn/user/', $avatarName);
                    $update = DB::table('users')
                        ->where('id', $userId)
                        ->update(['firstname' => $request['firstname'],
                            'lastname' => $request['lastname'],
                            'email' => $request['email'],
                            'avatar' => $avatarName]);
                    if ($update) {
                        $request->session()->flash('success', 'Your information has been updated successfully');
                        return redirect()->back();
                    } else {
                        return redirect()->back();
                    }
                }
            }

        }

    }

    public function UpdatePassword(Request $request)
    {

        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            return redirect()->back()->with("error", "Your current password does not matches with the password you provided. Please try again.");
        }

        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            return redirect()->back()->with("error", "New Password cannot be same as your current password. Please choose a different password.");
        }

        $validatedData = $request->validate([
            'current-password' => ['required'],
            'new-password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with("success", "Password changed successfully !");

    }
}
