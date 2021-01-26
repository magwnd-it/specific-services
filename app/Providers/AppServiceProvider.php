<?php

namespace App\Providers;

use Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // validation rule.
        Validator::extend('alpha_spaces', function ($attribute, $value) {

        // This will only accept alpha and spaces. 
        // If you want to accept hyphens use: /^[\pL\s-]+$/u.
        return preg_match('/^[\pL\s]+$/u', $value); 

        });


        // Use boostrap pagination
        Paginator::useBootstrap();

        // Pass notifications data to other views from NoticeComposer
        View::composer(
        ['includes.header', 'pages.notifications'],
        'App\Http\View\Composers\NoticeComposer');

         // Pass webiste info data to other views from NoticeComposer
        View::composer(
        ['includes.header', 'includes.head', 'includes.footer', 
        'admin.includes.header', 'admin.includes.head', 'includes.in-footer'],
        'App\Http\View\Composers\InfoComposer');

    }
}