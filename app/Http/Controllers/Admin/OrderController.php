<?php namespace Admin;

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use  Input, Redirect, Session, Validator, DB, Mail,File, Request,Response,URL,Form,Auth,Hash,Route;
use App\Order as Order,App\OrderItem as OrderItemMode, App\Address as Address;
class OrderController  extends Controller{
    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('admin_id')) {
                return Redirect::route('admin.auth.login');
            }
        });
    }
    public function index(){
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        $sortBy = Input::get('sortBy');
        $param['pageNo'] = 2;
        $param['list'] = Order::$status_labels;
        if($sortBy == 10 || $sortBy == ""){
            $param['orders'] = Order::where('active','=','1')->orderBy('created_at', 'desc')->paginate(10);
            $param['sortByValue'] =10;
        }else{
            $param['orders'] = Order::where('active', '=', '1')->orderBy('created_at', 'desc')->where('status','=', $sortBy)->paginate(10);
            $param['orders'] ->appends(array('sortBy' => $sortBy));
            $param['sortByValue'] =$sortBy;
        }
        return View::make('admin.order.index')->with($param);
    }
    public function detail($id){
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        $param['pageNo'] = 2;
        $order = Order::find($id);
        $param['order'] = $order;
        $param['list'] = Order::$status_labels;

        return View::make('admin.order.detail')->with($param);
    }

    public function detailUpdate(){
        $orderID = Input::get('orderID');
        $status = Input::get('status');
        $order = Order::find($orderID);
        $order->status = $status;
        $order->save();
        $messages = "Your order status has been updated by admin";
        $this->orderSendMessage($orderID, "Order Status Update",$messages);
        $alert['msg'] = 'This order status has been updated successfully';
        $alert['type'] = 'success';
        $alert['list'] = 'statusUpdateSuccessfully';
        return Redirect::route('admin.order.detail',$orderID)->with('alert', $alert);
    }
    function orderSendMessage($orderID, $title, $messages){
        $order = Order::find($orderID);
        if($title == "Shipping Detail"){
            $shippingDetail = $order->shippingDetail;
        }else if($title == "Order Status Update"){
            $shippingDetail = $order->orderStatus($order->status);
        }
        $email =  $order->user->email;
        $url = Route('orders.detail',$orderID);

        $data =array(
            'email' =>$email,
            'messages' =>$messages,
            'orderNumber' =>$orderID,
            'url' =>$url,
            'title' =>$title,
            'shippingDetail' =>$shippingDetail
        );
        Mail::send('emails.admin.orderShipping', $data, function($message) use ($email,$title){
            $message->from( getenv('MAIL_USERNAME'), $title);
            $message->to($email, 'Send Message')->subject($title);
        });
        return;
    }
    public function shippingDetailUpdate(){
        $orderID = Input::get('orderID');
        $shippingDetail = Input::get('shippingDetail');
        $order = Order::find($orderID);
        $order->shippingDetail = $shippingDetail;
        $order->save();
        $messages = "Your order shipping detail has been updated by admin";
        $this->orderSendMessage($orderID, "Shipping Detail",$messages);
        $alert['msg'] = 'This order shipping detail has been updated successfully';
        $alert['type'] = 'success';
        $alert['list'] = 'shippingDetailUpdateSuccessfully';
        return Redirect::route('admin.order.detail',$orderID)->with('alert', $alert);
    }

    public function delete($id){
        try {
            $order = Order::find($id);
            $order->active = 0;
            $order->save();
            $alert['msg'] = 'This order has been deleted successfully';
            $alert['type'] = 'success';
        } catch(\Exception $ex) {
            $alert['msg'] = 'This order has been already used';
            $alert['type'] = 'danger';
        }
        return Redirect::route('admin.order')->with('alert', $alert);
    }

    public function cancelAgree(){
        $orderID = Input::get('orderID');
        $order = Order::find($orderID);
        $refund_agree = Input::get('refund_agree');
        if($refund_agree == 1){
            $billingID = $order->billing_address_id;
            $shippingID = $order->shipping_address_id;
            $billingAddress = Address::find($billingID);
            $deliveryAddress = Address::find($shippingID);
            $transaction_id = 'muscle-' . uniqid() . '-' . microtime(true);
            $gateway_server = 'SagePay\Direct';
            $amount = $order->totalOrderDetail();
            $url = getenv('SAGEPAY_REFUND_URL');
            $params = array();
            $params['VPSProtocol'] = urlencode('3.00');
            $params['TxType'] = urlencode('REFUND');
            $params['Vendor'] = urlencode('amas');
            $params['VendorTxCode'] = urlencode($transaction_id);            //Sample value given by me
            $params['Amount'] = urlencode($amount);
            $params['Currency'] = urlencode('GBP');
            $params['Description'] = urlencode('Testing Refunds');
            $params['RelatedVPSTxId'] = urlencode($order->vps_tx_id);     //VPSTxId of main transaction
            $params['RelatedVendorTxCode'] = urlencode($order->transaction_id);         //VendorTxCode of main transaction
            $params['RelatedSecurityKey'] = urlencode($order->security_key);       //securitykey of main transaction
            $params['RelatedTxAuthNo'] = urlencode($order->tx_auth_no);            //vpsa

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url );
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($ch, CURLOPT_TIMEOUT, $curlTimeout);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $findString = "Status=OK";
            $pos = strpos($response, $findString);
            if($pos ===false){
                $alert['type'] = 'danger';
                $alert['list'] = 'refundAgreeSuccessfully';
                $alert['msg'] = 'This order payment did not refund correctly.';
                $result =0 ;
            }else{
                $order->status = 3;
                $order->save();
                $alert['type'] = 'success';
                $alert['list'] = 'refundAgreeSuccessfully';
                $alert['msg'] = 'This order has been canceled successfully.';
                $result = 1;
                $refundSuccess =1;
            }

        }else{
            $order->status = 3;
            $order->save();
            $alert['type'] = 'success';
            $alert['list'] = 'refundAgreeSuccessfully';
            $alert['msg'] = 'This order has been canceled successfully.';
            $refundSuccess =0;
            $result =0;
        }
        if($result == 1){
            if($order->user_id>0){
                $email =  $order->user->email;
            }

            $url = Route('orders.detail',$orderID);
            $title = "Order Canceled";
            if($refundSuccess == 1){
                $messages = " Your order canceled. You get full amount from our site.";
            }else{
                $messages = " Your order canceled.";
            }
            $data =array(
                'email' =>$email,
                'messages' =>$messages,
                'orderNumber' =>$orderID,
                'url' =>$url,
                'title' =>$title,
            );
            Mail::send('emails.admin.orderCancel', $data, function($message) use ($email,$title){
                $message->from( env('MAIL_USERNAME'), $title);
                $message->to($email, 'Send Message')->subject($title);
            });
        }

        return Redirect::route('admin.order.detail',$orderID)->with('alert', $alert);
    }

}