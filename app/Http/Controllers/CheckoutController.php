<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use JulioBitencourt\Cart\Cart;
use Omnipay\Omnipay;
use Omnipay\Common\CreditCard;
use Illuminate\Support\Facades\View;
use App\Address as AddressModel, App\Product as ProductModel, App\Country as CountryModel,
    App\User as UserModel, App\Order as OrderModel, App\OrderItem as OrderItemModel,
    App\ProductVariation as ProductVariationModel;
use  Input, Redirect, Session, Validator, DB, Mail,File,Response,URL,Form,Auth,Cookie;

class CheckoutController extends Controller
{
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;

        $this->fields = [
            'first_name' => ['label' => 'First Name', 'validation' => 'required|max:255'],
            'last_name' => ['label' => 'Last Name', 'validation' => 'required|max:255'],
            'address_1' => ['label' => 'Address (line 1)', 'validation' => 'required|max:255'],
            'address_2' => ['label' => 'Address (line 2)', 'validation' => ''],
            'city' => ['label' => 'City', 'validation' => 'required|max:255'],
            'state' => ['label' => 'State', 'validation' => 'required|size:2'],
            'zip' => ['label' => 'Zip', 'validation' => 'digits:5'],
            'country' => ['label' => 'Country', 'validation' => 'required|size:2'],
        ];
    }

    public function checkout()
    {
        $cart = $this->cart;
        $cart_product = array();
        foreach ($cart->all() as $key => $item) {
            $product = ProductVariationModel::find($item['sku']);
            $cart_product[$key]['product'] = $product;
        }
        $countries = CountryModel::orderBy('country_long_name')->get();
        return View::make('newCheckout')->with(['cart_product' => $cart_product, 'countries' => $countries, 'cart' =>$cart->all()]);
    }

    public function emailLogin()
    {
        $rules = [
            'email' => 'required',
            'password' => 'required'];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Response::json(['result' => 'failed', 'error' => $validator->getMessageBag()->toArray()]);
        } else {
            $email = Input::get('email');
            $password = Input::get('password');
            $credentials = array("email" => $email, 'password' => $password);

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $user_ID = $user->id;
                $billing_id = $user->billing_address_id;
                $shipping_id = $user->shipping_address_id;
                if ($user_ID != 0 && $billing_id != 0 && $shipping_id != 0) {
                    Session::set('billing_id', $billing_id);
                    Session::set('shipping_id', $shipping_id);
                    $billingAddress = AddressModel::find($billing_id);
                    $addressList = array();
                    $addressList['first_name'] = $billingAddress->first_name;
                    $addressList['last_name'] = $billingAddress->last_name;
                    $addressList['address'] = $billingAddress->address;
                    $addressList['city'] = $billingAddress->city;
                    $addressList['country'] = $billingAddress->country;
                    $addressList['postal_code'] = $billingAddress->postal_code;
                    $addressList['phone_number'] = $user->phone;
                    $addressList['email'] = $user->email;
                    $addressList['state'] = $billingAddress->state;
                    if ($user->billing_address_id == $user->shipping_address_id) {
                        $addressList['delivery'] = 0;
                    } else {
                        $addressList['delivery'] = 1;
                    }

                    if ($addressList['delivery'] == 1) {
                        $deliveryAddress = AddressModel::find($user->shipping_address_id);
                    } else {
                        $deliveryAddress = AddressModel::find($user->billing_address_id);
                    }
                    $addressList['delivery_first_name'] = $deliveryAddress->first_name;
                    $addressList['delivery_last_name'] = $deliveryAddress->last_name;
                    $addressList['delivery_address'] = $deliveryAddress->address;
                    $addressList['delivery_city'] = $deliveryAddress->city;
                    $addressList['delivery_state'] = $deliveryAddress->state;
                    $addressList['delivery_country'] = $deliveryAddress->country;
                    $addressList['delivery_postal_code'] = $deliveryAddress->postal_code;
                    return Response::json(['result' => 'success', 'addressList' => $addressList, 'userID' => ($user_ID + 100000 * 1)]);
                } else {
                    return Response::json(['result' => 'billingEmpty', 'userID' => ($user_ID + 100000 * 1)]);
                }
            } else {
                return Response::json(['result' => 'userEmpty']);
            }
        }
    }

    public function checkoutAddress()
    {
        $delivery = Input::get('delivery');
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required|size:2',
            'postal_code' => 'required'
        ];
        if ($delivery == 1) {
            foreach ($rules as $k => $v) {
                $rules['delivery_' . $k] = $v;
            }
        }
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Response::json(['result' => 'failed', 'error' => $validator->getMessageBag()->toArray()]);
        } else {
            $inputReturn = Input::get('return');
            if ($inputReturn == 1) {
                $billing_id = Session::get('billing_id');
                $billing = AddressModel::find($billing_id);
            } else {
                $billing = new AddressModel;
            }
            $billing->first_name = Input::get('first_name');
            $billing->last_name = Input::get('last_name');
            $billing->address = Input::get('address');
            $billing->city = Input::get('city');
            $billing->state = Input::get('state');
            $billing->country = Input::get('country');
            $billing->postal_code = Input::get('postal_code');
            $billing->save();
            $billing_id = $billing->id;

            $reallyID = Auth::user()->id;
            $user = UserModel::find($reallyID);
            $user->phone = Input::get('phone_number');
            $user->save();
            $deliveryAddressID = 0;
            if ($delivery == 1) {
                if ($inputReturn == 1) {
                    if ($reallyID != "") {
                        $user = UserModel::find($reallyID);
                        if ($user->billing_address_id == $user->shipping_address_id) {
                            $deliveryAddress = new AddressModel;
                        } else {
                            $deliveryAddress = AddressModel::find($user->billing_address_id);
                        }
                    } else {
                        $sessionShippingID = Session::get('shipping_id');
                        if (isset($sessionShippingID)) {
                            if (Session::get('shipping_id') == 0) {
                                $deliveryAddress = new AddressModel;
                            } else {
                                $deliveryAddress = AddressModel::find($sessionShippingID);
                            }
                        } else {
                            $deliveryAddress = new AddressModel;
                        }
                    }
                } else {
                    $deliveryAddress = new AddressModel;
                }
                $deliveryAddress->first_name = Input::get('delivery_first_name');
                $deliveryAddress->last_name = Input::get('delivery_last_name');
                $deliveryAddress->address = Input::get('delivery_address');
                $deliveryAddress->city = Input::get('delivery_city');
                $deliveryAddress->state = Input::get('delivery_state');
                $deliveryAddress->country = Input::get('delivery_country');
                $deliveryAddress->postal_code = Input::get('delivery_postal_code');
                $deliveryAddress->save();
                $deliveryAddressID = $deliveryAddress->id;
            }

            Session::set('billing_id', $billing_id);
            Session::set('shipping_id', $deliveryAddressID);
            $user = UserModel::find($reallyID);
            $user->billing_address_id = $billing_id;
            if ($deliveryAddressID == 0) {
                $deliveryAddressID = $billing_id;
            }
            $user->shipping_address_id = $deliveryAddressID;
            $user->save();
            return Response::json(['result' => 'success', 'flag' => 0]);

        }
    }

    public function do_checkout()
    {
        $rule = $rules = [
            'name_on_card' => 'required',
            'card_number' => 'required|numeric',
            'expiration_date' => 'required|numeric',
            'expiration_year' => 'required|numeric',
            'csv' => 'required|numeric',
        ];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Response::json(['result' => 'failed', 'error' => $validator->getMessageBag()->toArray()]);
        } else {
            $amount = Input::get('payment_value');
            $nameOnCard = Input::get('name_on_card');
            $cardNumber = Input::get('card_number');
            $month = Input::get('expiration_date');
            $year = Input::get('expiration_year');
            $csv = Input::get('csv');
            $billingID = Session::get('billing_id');
            $shippingID = Session::get('shipping_id');
            $billingAddress = AddressModel::find($billingID);
            if ($shippingID == 0) {
                $deliveryAddress = $billingAddress;
                $shippingReallyID = $billingID;
            } else {
                $deliveryAddress = AddressModel::find($shippingID);
                $shippingReallyID = $shippingID;
            }
            $gateway_server = 'SagePay\Direct';
            $transaction_id = 'muscle-' . uniqid() . '-' . microtime(true);
            $card = new CreditCard([
                'firstName' => $billingAddress->first_name,
                'lastName' => $billingAddress->last_name,

                'number' => $cardNumber,
                'expiryMonth' => $month,
                'expiryYear' => $year,
                'CVV' => $csv,

                'billingAddress1' => $billingAddress->address,
                'billingAddress2' => '',
                'billingState' => $billingAddress->state,
                'billingCity' => $billingAddress->city,
                'billingPostcode' => $billingAddress->postal_code,
                'billingCountry' => $billingAddress->country,

                'shippingAddress1' => $deliveryAddress->address,
                'shippingAddress2' => '',
                'shippingState' => $deliveryAddress->state,
                'shippingCity' => $deliveryAddress->city,
                'shippingPostcode' => $deliveryAddress->postal_code,
                'shippingCountry' => $deliveryAddress->country,
            ]);

            $gateway = OmniPay::create($gateway_server)
                ->setVendor(getenv('SAGEPAY_MERCHANT_NAME'))
                ->setTestMode(true);

            $requestMessage = $gateway->purchase([
                'amount' => $amount,
                'currency' => 'GBP',
                'card' => $card,
                'transactionId' => $transaction_id,
                'description' => "Retail order",
                'clientIp' => '127.0.0.1',
            ]);
            $responseMessage = $requestMessage->send();
            if ($responseMessage->isSuccessful()) {
                //$order = OrderModel::find($orderID);
                $transactionReference = ($responseMessage->getTransactionReference());
                $transactionReference = str_replace(array('{', '}'), '', $transactionReference);
                $resultTransactionReference = explode(',', $transactionReference);
                $responseArrayList = array();
                for ($jk = 0; $jk < count($resultTransactionReference); $jk++) {
                    $securityKeyList = explode(':', $resultTransactionReference[$jk]);
                    $securityKey = str_replace(array('"', '"'), '', $securityKeyList[1]);
                    $responseArrayList[$jk] = $securityKey;
                }

                $order = new OrderModel;
                $order->user_id = Auth::user()->id;
                $order->status = 0;
                $order->transaction_id = $transaction_id;
                $order->billing_address_id = $billingID;
                $order->shipping_address_id = $shippingReallyID;
                $order->security_key = $responseArrayList[0];
                $order->tx_auth_no = $responseArrayList[1];
                $order->vps_tx_id = $responseArrayList[2];
                $order->active = 1;
                $order->save();
                $orderID = $order->id;
                foreach ($this->cart->all() as $key => $item) {
                    $productVariation = ProductVariationModel::find($item['sku']);
                    $product = ProductModel::find($productVariation->product_id);
                    $orderItem = new OrderItemModel;
                    $brand = $product->brand;
                    if(count($brand)>0){
                        $brandName = $brand->name;
                    }else{
                        $brandName= "";
                    }
                    $flavor = $productVariation->flavor;
                    if(count($flavor) >0){
                        $flavorName = $flavor->name;
                    }else{
                        $flavorName = "";
                    }

                    $orderItem->order_id = $orderID;
                    $orderItem->product_variation_id = $item['sku'];
                    $orderItem->quantity = $item['quantity'];
                    $orderItem->price = $productVariation->price;
                    $orderItem->sku = $product->sku;
                    $orderItem->name = $product->name;
                    $orderItem->slug = $product->slug;
                    $orderItem->subsku = $productVariation->subsku;
                    $orderItem->brand = $brandName;
                    $orderItem->size = $productVariation->size;
                    $orderItem->flavor = $flavorName;
                    $orderItem->servings = $productVariation->servings;
                    $orderItem->weight = $productVariation->weight;
                    $orderItem->width = $productVariation->width;
                    $orderItem->length = $productVariation->length;
                    $orderItem->depth = $productVariation->depth;
                    $orderItem->image = $productVariation->image;
                    $orderItem->save();
                }
                /****user email send function ****/
                $user = UserModel::find(Auth::user()->id);
                $adminUser = UserModel::whereRaw('is_admin=?', array(1))->first();
                $emails = array();
                $emails[0] = $adminUser->email;
                $emails[1] = $user->email;

                $url = URL::route('orders.detail', $orderID);
                $title = "Order Checkout Success.";
                $data = array(
                    'orderNumber' => $orderID,
                    'url' => $url,
                    'title' => $title,
                    'transaction_id' => $transaction_id
                );

                Mail::send('emails.checkOutSuccess', $data, function ($message) use ($emails, $title) {
                    $message->from(getenv('MAIL_USERNAME'), $title);
                    $message->bcc($emails, 'Send Message')->subject($title);
                });
                Session::forget(array('billing_id','shipping_id'));
                Session::set('transaction_id', $transaction_id);
                Session::set('order', $orderID);
                return Response::json(['result' => 'success', 'url' => URL::route('checkout.thanks')]);
            } else {
                $error = $responseMessage->getMessage();
                return Response::json(['result' => 'paymentFailed', 'error' => $error]);
            }
        }
    }

    public function doPaypal(){
        $rules = [
            'payment_value' => 'required'];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Response::json(['result' => 'failed', 'error' => $validator->getMessageBag()->toArray()]);
        } else{
            $orderLast = OrderModel::orderBy('created_at', 'desc')->get();
            $transaction_id = 'muscle-' . uniqid() . '-' . microtime(true);

            $billingID = Session::get('billing_id');
            $shippingID = Session::get('shipping_id');
            $billingAddress = AddressModel::find($billingID);
            if($shippingID == 0){
                $deliveryAddress = $billingAddress;
                $shippingReallyID = $billingID;
            }else{
                $deliveryAddress = AddressModel::find($shippingID);
                $shippingReallyID = $shippingID;
            }
            $order = new OrderModel;
            $order->status = 0;
            $order->user_id = Auth::user()->id;
            $order->transaction_id = $transaction_id;
            $order->billing_address_id = $billingID;
            $order->shipping_address_id = $shippingReallyID;
            $order->active = 0;
            $order->save();
            $orderID  = $order->id;
            foreach( $this->cart->all() as $key => $item){
                $productVariation =ProductVariationModel::find($item['sku']);
                $product  = ProductModel::find($productVariation->product_id);
                $orderItem = new OrderItemModel;
                $brand = $product->brand;
                if(count($brand)>0){
                    $brandName = $brand->name;
                }else{
                    $brandName= "";
                }
                $flavor = $productVariation->flavor;
                if(count($flavor) >0){
                    $flavorName = $flavor->name;
                }else{
                    $flavorName = "";
                }
                $orderItem->order_id = $orderID;
                $orderItem->product_variation_id = $item['sku'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $productVariation->price;
                $orderItem->sku = $product->sku;
                $orderItem->name = $product->name;
                $orderItem->slug = $product->slug;
                $orderItem->subsku = $productVariation->subsku;
                $orderItem->brand = $brandName;
                $orderItem->size = $productVariation->size;
                $orderItem->flavor = $flavorName;
                $orderItem->servings = $productVariation->servings;
                $orderItem->weight = $productVariation->weight;
                $orderItem->width = $productVariation->width;
                $orderItem->length = $productVariation->length;
                $orderItem->depth = $productVariation->depth;
                $orderItem->image = $productVariation->image;
                $orderItem->save();
            }
            Session::set('transaction_id', $transaction_id);
            Session::set('order', $orderID);
            return Response::json(['result' =>'success','orderID'=>$orderID,'transactionID' =>$transaction_id]);
        }
    }
    public function paypalNotify()
    {
        if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])) {

        } else {
            // Response from Paypal

            // read the post from PayPal system and add 'cmd'
            $req = 'cmd=_notify-validate';

            foreach ($_POST as $key => $value) {
                $value = urlencode(stripslashes($value));
                $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value);// IPN fix
                $req .= "&$key=$value";
            }

            // assign posted variables to local variables
            $data['item_name'] = $_POST['item_name'];
            $data['item_number'] = $_POST['item_number'];
            $data['payment_status'] = $_POST['payment_status'];
            $data['payment_amount'] = $_POST['mc_gross'];
            $data['payment_currency'] = $_POST['mc_currency'];
            $data['txn_id'] = $_POST['txn_id'];
            $data['receiver_email'] = $_POST['receiver_email'];
            $data['payer_email'] = $_POST['payer_email'];
            $data['custom'] = $_POST['custom'];
            $data['max_id'] = $_POST['custom'];
            $orderID = $data['max_id'];
            $header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
            $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $header .= "Host: www.sandbox.paypal.com\r\n";
            $header .= "Connection: close\r\n";
            $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

            $fp = fsockopen('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);

            if (!$fp) {
                // HTTP ERROR

            } else {
                fputs($fp, $header . $req);

                while (!feof($fp)) {

                    $res = fgets($fp, 1024);
                    $res = str_replace(array("\n", "\r"), '', $res);

                    if (strcmp($res, "VERIFIED") == 0) {
                        $order = OrderModel::find($orderID);
                        $order->active = 1;
                        $order->save();
                    }
                }
                fclose($fp);
            }
        }
    }
  public function proceed(Request $request)
  {
    $this->validate($request, [
      'tos'=>'required|in:yes'
    ]);
      return Redirect::route('checkout');
  }
  

    function thanks()
    {
        $this->cart->destroy();
        $transactionID = Session::get('transaction_id');
        $orderID = Session::get('order');
        Session::forget(array('transaction_id','order'));
        $order = OrderModel::find($orderID);
        $billingAddress = AddressModel::find($order->billing_address_id);
        $delivery = AddressModel::find($order->shipping_address_id);
        return view('thanks')->with([
            'transaction_id'=>$transactionID,
            'order'=>$order,
            'billingAddress' =>$billingAddress,
            'delivery' =>$delivery,
        ]);
    }
}
