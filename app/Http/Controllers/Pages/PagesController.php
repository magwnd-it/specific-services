<?php

namespace App\Http\Controllers\Pages;

use App\Models\Page;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    // View privacy page
    public function privacy() {
        $privacy = Page::find(1);
        return view('pages.privacy', ['privacy' => $privacy]);
    }

    // View terms page
    public function terms() {
        $terms = Page::find(1);
        return view('pages.terms', ['terms' => $terms]);
    }
}