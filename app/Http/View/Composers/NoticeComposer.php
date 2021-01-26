<?php

namespace App\Http\View\Composers;

use Auth;
use DB;
use App\Repositories\UserRepository;
use Illuminate\View\View;

class NoticeComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */

    // Get notifications when there is new reply on ticket
    
    public function compose(View $view)
    {
        if (Auth::check()) {
            $notice = DB::table('tickets')
                ->where('user_id', Auth::id())
                ->where('notice', 2)
                ->get();
        } else {
            $notice = collect();
        }

        $view->with('notice', $notice);
    }
}