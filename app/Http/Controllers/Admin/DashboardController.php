<?php namespace Admin;

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use  Input, Redirect, Session, Validator, DB, Mail,File, Request,Response,URL,Form,Auth,Hash;
use App\User as User, App\Order as Order;
class DashboardController  extends Controller{

    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('admin_id')) {
                return Redirect::route('admin.auth.login');
            }
        });
    }

    public function index() {
        $param['pageNo'] = 1;
        $param['orders']  = Order::whereIn('status',array(0,1,2))->where('active','=','1')->orderBy('created_at', 'desc')->paginate(10);
        return View::make('admin.dashboard.index')->with($param);
    }
    public function profile(){
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        $param['pageNo'] = 1;
        return View::make('admin.dashboard.profile')->with($param);
    }
    public function profileStore(){
        $rules = [  'currentPassword'  => 'required',
                    'newPassword'  => 'required',
                    'confirmNewPassword' => 'required',
        ];
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }else{
            $id = Session::get('admin_id');
            $currentPassword= Input::get('currentPassword');
            $newPassword = Input::get('newPassword');
            $confirmNewPassword = Input::get('confirmNewPassword');
            if($newPassword != $confirmNewPassword) {
                $alert['msg'] = 'Please check again your New Password and Confirm New Password';
                $alert['type'] = 'danger';
            }else{
                    $user = User::find($id);
                    $user->password=Hash::make($newPassword);
                    $user->save();
                    $alert['msg'] = 'User has been updated successfully';
                    $alert['type'] = 'success';
            }
        }
        return Redirect::route('admin.profile')->with('alert', $alert);
    }
}
