<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function index() {

        $users = User::get();

        return view('admin.users', ['users'=> $users]);
    }

    public function UserData($id) {

        $user = User::find($id);

        return view('admin.update-user', ['user'=>$user]);
        
    }

    public function update(Request $request) {

        $data = User::find($request->id);

        if(!$data == null) {

            $validate = $request->validate(
            ['permission' => ['required']]);

            $data->permission=$request['permission'];
            $data->save();
            session()->flash('success', 'Permission has been Updated successfully');
            return redirect()->back();

        }else {
            session()->flash('error', 'There is an error on the inputs, please refresh the page');
            return redirect()->back();
        }
        
    }
}