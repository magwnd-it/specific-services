<?php

namespace App\Http\View\Composers;

use App\Repositories\UserRepository;
use Illuminate\View\View;
use App\Models\Settings;

class InfoComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */

    // Get website information from settings table
    
    public function compose(View $view)
    {
        $info = Settings::find(1);

        $view->with('info', $info);
    }
}