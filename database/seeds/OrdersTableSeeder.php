<?php

use Illuminate\Database\Seeder;
use App\Address as AddressModel, App\Order as OrdersModel, App\OrderItem as OrderItemsModel, App\User as UserModel;
class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        App\Address::truncate();
        App\OrderItem::truncate();
		App\Order::truncate();

        $address = new AddressModel;
        $address->first_name ='John';
        $address->last_name ='Imak';
        $address->address ='John';
        $address->city ='centrol';
        $address->state ='NY';
        $address->country ='US';
        $address->postal_code ='11934';
        $address->save();

        $addressID = $address->id;
        $users= UserModel::orderBy('created_at','DESC')->first();
		$userID = $users->id;
        $users->billing_address_id = $addressID;
        $users->shipping_address_id = $addressID;
        $users->save();
        $order = new OrdersModel;
        $order->user_id = $userID;
        $order->status = '0';
        $order->active = '1';
        $order->transaction_id = '';
        $order->billing_address_id = $addressID;
        $order->shipping_address_id = $addressID;
        $order->save();

		$orderID = $order->id;
        /****1 order_items**********/
        $orderItem = new OrderItemsModel;
        $orderItem ->order_id = $orderID;
        $orderItem ->slug = 'profusion-reloaded';
        $orderItem ->product_variation_id ='89';
        $orderItem ->quantity = '1';
        $orderItem ->price = '18.95';
        $orderItem ->sku = '1047';
        $orderItem ->name =  'Vyomax All in One';
        $orderItem ->brand= 'Vyomax Nutrition';
        $orderItem ->subsku = '2-V';
        $orderItem ->size = '2kg';
        $orderItem ->flavor = 'Vanilla';
        $orderItem ->servings = '45';
        $orderItem ->width = '';
        $orderItem ->weight = '2100';
        $orderItem ->length = '';
        $orderItem ->depth = '';
        $orderItem ->image = '760d0829cf962a60b9305a08ef4c588f-NRG-anabolic-whey-300x300.jpg';
        $orderItem->save();

        $orderItem = new OrderItemsModel;
        $orderItem ->order_id = $orderID;
        $orderItem ->slug = 'anabolic-mass-2';
        $orderItem ->product_variation_id ='77';
        $orderItem ->quantity = '1';
        $orderItem ->price = '18.95';
        $orderItem ->sku = '1047';
        $orderItem ->name =  '112-N';
        $orderItem ->brand= 'Vyomax Nutrition';
        $orderItem ->subsku = '112-N';
        $orderItem ->size = '112g';
        $orderItem ->flavor = 'Natural';
        $orderItem ->servings = '40';
        $orderItem ->width = '';
        $orderItem ->weight = '200';
        $orderItem ->length = '';
        $orderItem ->depth = '';
        $orderItem ->image = 'e6530b9e02740d3b768feb41496ae34b-Purus-Labs-NOXYGEN-300x300.jpg';
        $orderItem->save();
    }
}
