<?php
namespace App\Http\Controllers;

use App\Classes\Cart\Cart;
use App\Classes\Cart\ShippingOption;
use App\Classes\Cart\ShippingOptionsFactory;
use App\ProductVariation;
use App\Http\Requests;
use App\Voucher;
use Illuminate\Http\Request;
use App\Order as OrdersModel;

class CartController extends Controller
{
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }
    function addOrder($order_id){

        $order = OrdersModel::find($order_id);
        foreach($order->items as $key=>$itemList){
            $v = ProductVariation::find($itemList->product_variation_id);
            if(!$v)
            {
                session()->flash('danger', 'Sorry, that product does not exist.');
                return redirect()->route('cart.view');
            }

            $item = [
                'sku' => $v->id,
                'description' => $v->product->name,
                'price' => $v->price,
                'quantity' => 1,
                'weight' => $v->weight,
            ];

            $result = $this->cart->insert($item);
        }
        session()->flash('success', 'Product added.');
        return redirect()->route('cart.view');
    }
    public function add($variation_id)
    {
        $productVariation = ProductVariation::find($variation_id);
        if(!$productVariation)
        {
            session()->flash('danger', 'Sorry, that product does not exist.');
            return redirect()->route('cart.view');
        }

        $item = [
            'sku' => $productVariation->id,
            'description' => $productVariation->product->name,
            'price' => $productVariation->price,
            'quantity' => 1,
            'weight' => $productVariation->weight,
        ];

        $this->cart->insert($item);

        session()->flash('success', 'Product added.');
        return redirect()->route('cart.view');
    }

    public function view()
    {
        return view('cart.view')->with(['cart'=>$this->cart]);
    }

    public function update()
    {
        $items = request()->input('items');
        foreach($items as $v_id=>$qty)
        {
            $this->cart->update($v_id, $qty);
        }
        session()->flash('success', 'Cart updated.');
        return redirect()->route('cart.view');
    }

    public function applyVoucher(Request $request)
    {
        $this->validate($request, [
            'voucher' => 'required|exists:vouchers,code'
        ]);

        $voucher = Voucher::where('code', $request->get('voucher'))->first();

        $this->cart->applyVoucher($voucher);

        session()->flash('success', 'Voucher `'.$voucher->code.'` successfully applied.');

        return redirect()->route('cart.view');
    }

    public function applyShippingMethod(Request $request)
    {
        $this->validate($request, [
            'shipping_method' => 'required|in:'.ShippingOptionsFactory::NEXT_DAY.','.ShippingOptionsFactory::EXPRESS,
        ]);

        /** @var string $shippingMethod */
        $shippingMethod = $request->input('shipping_method');

        /** @var ShippingOption[] $options */
        $options = (new ShippingOptionsFactory($this->cart))->getAll();

        $this->cart->setShippingOption( $options[$shippingMethod] );

        session()->flash('success', '`'.$options[$shippingMethod]->getName().'` available.');

        return redirect()->route('cart.view');
    }
}
