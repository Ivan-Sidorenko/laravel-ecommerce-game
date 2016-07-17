<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    static $status_labels = [
        '0'=>'New',
        '1'=>'Processing',
        '2'=>'Shipped',
        '3'=>'Canceled',
        '4'=>'Returned',
        '5'=>'Request Cancel'
    ];

    protected static function boot() {
        parent::boot();

        static::deleting(function($product) { // before delete() method call this
             $product->items()->delete();
        });
    }

    public function items()
    {
        return $this->hasMany('App\OrderItem', 'order_id');
    }
    public function billingAddress()
    {
        return $this->belongsTo('App\Address', 'billing_address_id');
    }
    public function totalOrderDetail()
    {
        $total = 0;
        foreach($this->items as $it)
        {
            $total += $it->quantity * $it->price;
        }
        
        return $total;
    }   
    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function orderStatus($status)
    {
        return self::$status_labels[$status];
    }

}
