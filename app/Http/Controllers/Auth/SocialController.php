<?php
namespace App\Http\Controllers\Auth;

use Auth;
use Exception;
use DB;
use File;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Laravel\LegacyUi\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;



class SocialController extends Controller
{

    public function redirectToProvider($provider) 
    {
        if($provider == 'facebook') {
            if(env('FACEBOOK_CLIENT_ID') == null) {
                echo "Please adjust Facebook login settings from the admin panel";
            }else {
               return Socialite::driver($provider)->redirect();
            }
        }elseif($provider == 'google') {
            if(env('GOOGLE_CLIENT_ID') == null) {
                echo "Please adjust Google login settings from the admin panel";
            }else {
               return Socialite::driver($provider)->redirect();
            }
        }else {
            return redirect('/');
        }
    }

    public function handleProviderCallback($provider)
    {
        
        if($provider == 'facebook') {
            
            $user = Socialite::driver($provider)->fields(['first_name', 'last_name', 'email'])->user();

            try {
                $finduser = User::where('facebook_id', $user->id)->first();
                if($finduser) {
                    Auth::login($finduser);
                    return redirect('/dashboard/#success');
                }else {
                    $findemail = User::where('email', $user->email)->first();
                    if($findemail) {
                        $update = User::where('email', $user->email)
                                ->update(['facebook_id'=> $user->id]);
                        if($update) {
                            Auth::login($findemail);
                            return redirect('/dashboard/#success');
                        }
                    }else {
                        $fileContents = file_get_contents($user->avatar);
                        File::put(public_path() . '/cdn/user/' . md5(time()) . ".jpg", $fileContents);
                        $avatar = md5(time()) . ".jpg";
                        $newUser = User::create([
                           'firstname' => $user['first_name'],
                           'lastname' => $user['last_name'],
                           'email' => $user->email,
                           'avatar'=> $avatar,
                           'facebook_id'=> $user->id,
                        ]);
                        if($newUser) {
                            Auth::login($newUser);
                            return redirect('/dashboard/#success');
                        }
                    }
                }
                
            } catch(Exception $e) {
                dd($e->getMessage());
            }

        }elseif($provider == 'google') {
            
            $user = Socialite::driver($provider)->user();

            try {
                $finduser = User::where('google_id', $user->id)->first();
                if($finduser) {
                    Auth::login($finduser);
                    return redirect('/dashboard');
                }else {
                    $findemail = User::where('email', $user->email)->first();
                    if($findemail) {
                        $update = User::where('email', $user->email)
                                ->update(['google_id'=> $user->id]);
                        if($update) {
                            Auth::login($findemail);
                            return redirect('/dashboard');
                        }
                    }else {
                        $fileContents = file_get_contents($user->avatar);
                        File::put(public_path() . '/cdn/user/' . md5(time()) . ".jpg", $fileContents);
                        $avatar = md5(time()) . ".jpg";
                        $newUser = User::create([
                           'firstname' => $user['given_name'],
                           'lastname' => $user['family_name'],
                           'email' => $user->email,
                           'avatar'=> $avatar,
                           'google_id'=> $user->id,
                        ]);
                        if($newUser) {
                            Auth::login($newUser);
                            return redirect('/dashboard');
                        }
                    }
                }
                
            } catch(Exception $e) {
                dd($e->getMessage());
            }

        }else {
            return redirect()->back();
        }
        
    }

}