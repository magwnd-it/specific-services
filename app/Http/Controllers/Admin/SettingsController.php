<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    public function index()
    {

        $settings = Settings::find(1);

        return view('admin.settings', ['settings' => $settings]);
    }

    public function UpdateData(Request $request)
    {

        $validate = $request->validate(
            ['site_name' => ['required', 'string', 'max:200'],
                'site_logo' => ['max:2048', 'mimes:png'],
                'site_fav' => ['max:2048', 'mimes:ico'],
                'description' => ['max:300'],
                'keywords' => ['max:300']]);

        $data = Settings::find(1);

        if ($request['site_logo'] == null) {

            if ($request['site_fav'] == null) {

                $update = DB::table('settings')
                    ->where('id', 1)
                    ->update(['site_name' => strip_tags($request['site_name']),
                        'description' => strip_tags($request['description']),
                        'keywords' => strip_tags($request['keywords'])]);
                if ($update) {

                    $request->session()->flash('success', 'Information Updated Successfully');
                    return redirect()->back();

                } else {
                    $request->session()->flash('info', 'Nothing updated, try change something');
                    return redirect()->back();
                }

            } else {

                $site_fav = $data->site_fav;

                $deleteOldFav = File::delete('images/' . $site_fav);

                if ($deleteOldFav) {

                    $request->site_fav->move('images/', $site_fav);

                    $update = DB::table('settings')
                        ->where('id', 1)
                        ->update(['site_name' => strip_tags($request['site_name']),
                            'site_fav' => $site_fav,
                            'description' => $request['description'],
                            'keywords' => $request['keywords']]);

                } else {

                    $favNam = "favicon.ico";

                    $request->site_fav->move('images/', $favNam);

                    $update = DB::table('settings')
                        ->where('id', 1)
                        ->update(['site_name' => strip_tags($request['site_name']),
                            'site_fav' => $favNam,
                            'description' => $request['description'],
                            'keywords' => $request['keywords']]);

                }

            }

        } else {

            if ($request['site_fav'] == null) {

                $site_logo = $data->site_logo;

                $deleteOldLogo = File::delete('images/' . $site_logo);

                if ($deleteOldLogo) {

                    $request->site_logo->move('images/', $site_logo);

                    $update = DB::table('settings')
                        ->where('id', 1)
                        ->update(['site_name' => strip_tags($request['site_name']),
                            'site_logo' => $site_logo,
                            'description' => strip_tags($request['description']),
                            'keywords' => strip_tags($request['keywords'])]);

                } else {

                    $logoNam = "logo.png";

                    $request->site_logo->move('images/', $logoNam);

                    $update = DB::table('settings')
                        ->where('id', 1)
                        ->update(['site_name' => strip_tags($request['site_name']),
                            'site_logo' => $logoNam,
                            'description' => $request['description'],
                            'keywords' => $request['keywords']]);

                }

            } else {

                $site_fav = $data->site_fav;
                $site_logo = $data->site_logo;

                $deleteOldFav = File::delete('images/' . $site_fav);
                $deleteOldLogo = File::delete('images/' . $site_logo);

                if ($deleteOldFav) {

                    if ($deleteOldLogo) {

                        $request->site_fav->move('images/', $site_fav);
                        $request->site_logo->move('images/', $site_logo);

                        $update = DB::table('settings')
                            ->where('id', 1)
                            ->update(['site_name' => $request['site_name'],
                                'site_logo' => $site_logo,
                                'site_fav' => $site_fav,
                                'description' => strip_tags($request['description']),
                                'keywords' => strip_tags($request['keywords'])]);

                    } else {

                        $logoNam = "logo.png";
                        $site_fav = $data->site_fav;

                        $request->site_logo->move('images/', $logoNam);
                        $request->site_fav->move('images/', $site_fav);

                        $update = DB::table('settings')
                            ->where('id', 1)
                            ->update(['site_name' => strip_tags($request['site_name']),
                                'site_logo' => $logoNam,
                                'site_fav' => $site_fav,
                                'description' => $request['description'],
                                'keywords' => $request['keywords']]);

                    }

                } else {

                    if ($deleteOldLogo) {

                        $favNam = "favicon.ico";
                        $site_logo = $data->site_logo;

                        $request->site_fav->move('images/', $favNam);
                        $request->site_logo->move('images/', $site_logo);

                        $update = DB::table('settings')
                            ->where('id', 1)
                            ->update(['site_name' => $request['site_name'],
                                'site_fav' => $favNam,
                                'site_logo' => $site_logo,
                                'description' => $request['description'],
                                'keywords' => $request['keywords']]);

                    } else {

                        $favNam = "favicon.ico";
                        $logoNam = "logo.png";

                        $request->site_fav->move('images/', $favNam);
                        $request->site_logo->move('images/', $logoNam);

                        $update = DB::table('settings')
                            ->where('id', 1)
                            ->update(['site_name' => strip_tags($request['site_name']),
                                'site_fav' => $favNam,
                                'site_logo' => $logoNam,
                                'description' => $request['description'],
                                'keywords' => $request['keywords']]);

                    }

                }

            }

        }

        $request->session()->flash('success', 'Information Updated Successfully');
        return redirect()->back();

    }
}
