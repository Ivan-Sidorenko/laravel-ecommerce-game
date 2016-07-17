<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Order as Order;
use  Input, Redirect, Session, Validator, DB, Mail,File,Response,URL,Form,Auth,Cookie;
class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userID = $user->id;
        $orders = Order::where('user_id','=', $userID)->orderBy('created_at', 'desc')->where('active','=','1')->get();
        return view('order.index', compact('orders'));
    }
    
    public function detail($id) 
    {
        $order = Order::find($id);
        
        return view('order.detail', compact('order'));       
    }
    public  function cancel(){
        $orderID = Input::get('orderID');
        $order = Order::find($orderID);
        $orderStatus = $order->status;
        $order->status = 5;
        $order->previous_status = $orderStatus;
        $order->save();
        return Redirect::route('orders.index');
    }
}
