<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use  Input, Redirect, Session, Validator, DB, Mail,File, Request,Response,URL, Auth, Hash,Form;

class AuthController extends Controller{
    public function index() {
        if (Session::has('admin_id')) {
            return Redirect::route('admin.dashboard');
        } else {
            return Redirect::route('admin.auth.login');
        }
    }
    public function login() {
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
            return View::make('admin.auth.login')->with($param);
        } else {
            return View::make('admin.auth.login');
        }
    }
    public function doLogin()
    {
        $rules = ['email' => 'required',
            'password' => 'required',
        ];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $email = Input::get('email');
            $password = Input::get('password');
            if($email !="" && $password != ""){
                $credentials = array("email" =>$email, 'password' =>$password );

                if (Auth::attempt($credentials)) {

                    $user_admin = Auth::user()->is_admin;
                    if($user_admin == 1){
                        Session::set('admin_id', 1);
                        return Redirect::route('admin.dashboard');
                    }else{
                        $alert['msg'] = 'Invalid Email and Password';
                        $alert['type'] = 'danger';
                    }
                }else{
                    $alert['msg'] = 'Invalid Email and Password';
                    $alert['type'] = 'danger';
                }
            }else{
                $alert['msg'] = 'Invalid Email and Password';
                $alert['type'] = 'danger';
            }
            return Redirect::route('admin.auth')->with('alert', $alert);
        }
    }

    public function logout(){
            Session::forget('admin_id');
            return Redirect::route('admin.auth.login');
    }
}