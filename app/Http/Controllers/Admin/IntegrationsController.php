<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Integrate;

class IntegrationsController extends Controller
{
    // View integrations page
    public function index() {
        $integrates = Integrate::find(1);
        return view('admin.integrations', ['integrates' => $integrates]);
    }

    // Set Env function 
    private function setEnv($name, $value) {
        $path = base_path('.env');
        if (file_exists($path)) {
            file_put_contents($path, str_replace(
            $name . '=' . env($name), $name . '=' . $value, file_get_contents($path)));
        }
    }

    // Update integrations 
    public function update(Request $request) {
        $update = DB::table('integrates')
                    ->where('id', 1)
                    ->update([
                        'FACEBOOK_CLIENT_ID' => $request['FACEBOOK_CLIENT_ID'], 
                        'FACEBOOK_CLIENT_SECRET' => $request['FACEBOOK_CLIENT_SECRET'], 
                        'FACEBOOK_REDIRECT_URL' => $request['FACEBOOK_REDIRECT_URL'], 
                        'GOOGLE_CLIENT_ID' => $request['GOOGLE_CLIENT_ID'], 
                        'GOOGLE_CLIENT_SECRET' => $request['GOOGLE_CLIENT_SECRET'], 
                        'GOOGLE_REDIRECT' => $request['GOOGLE_REDIRECT']]);
        if($update) {   
                              
            $this->setEnv('FACEBOOK_CLIENT_ID', $request['FACEBOOK_CLIENT_ID']);
            $this->setEnv('FACEBOOK_CLIENT_SECRET', $request['FACEBOOK_CLIENT_SECRET']);
            $this->setEnv('FACEBOOK_REDIRECT_URL', $request['FACEBOOK_REDIRECT_URL']);
            $this->setEnv('GOOGLE_CLIENT_ID', $request['GOOGLE_CLIENT_ID']);
            $this->setEnv('GOOGLE_CLIENT_SECRET', $request['GOOGLE_CLIENT_SECRET']);
            $this->setEnv('GOOGLE_REDIRECT', $request['GOOGLE_REDIRECT']);
            
            $request->session()->flash('success', 'information has ben updated');
            return redirect()->back();
            
        } else {
            return redirect()->back();
        }  
    }
}