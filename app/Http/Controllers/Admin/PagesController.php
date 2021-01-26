<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;

class PagesController extends Controller
{
    // View admin pages
    public function index() {
        $pages = Page::find(1);
        return view('admin.pages', ['pages' => $pages]);
    }

    // Update admin pages
    public function update(Request $request) {
        $data = Page::find(1);
        $data->privacy=$request['privacy'];
        $data->terms=$request['terms'];
        $data->save();
        session()->flash('success', 'Pages has been Updated successfully');
            return redirect()->back();
    }
}