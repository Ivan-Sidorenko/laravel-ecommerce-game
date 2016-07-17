<?php

namespace App\Classes\Cart;
use App\ProductVariation;
use JulioBitencourt\Cart\Cart as JulioBitencourtCart;
use JulioBitencourt\Cart\Storage\StorageInterface as Storage;
use Illuminate\Session\Store as Session;
use App\Voucher;

/**
 * Cart
 *
 * @author Alexander Begoon <alexander.begoon@gmail.com>
 */
class Cart extends JulioBitencourtCart implements CartInterface
{
    /**
     * @var Voucher[]
     */
    protected $vouchers = [];

    /**
     * @var ShippingOption
     */
    protected $shippingOption;

    /**
     * Cart constructor.
     * @param Storage $storage
     * @param Session $session
     */
    public function __construct(Storage $storage, Session $session)
    {
        parent::__construct($storage);

        $this->session = $session;

        $this->shippingOption = $this->session->get('shipping_option');
        $this->vouchers = $this->session->get('vouchers', []);
    }

    /**
     * @return float
     */
    public function totalWeight()
    {
        $weight = 0;

        foreach (static::$cart as $item) {
            $weight += $item['weight'] * $item['quantity'];
        }

        return $weight;
    }

    /**
     * @param Voucher $voucher
     * @return Cart
     */
    public function applyVoucher(Voucher $voucher)
    {
        $this->vouchers[$voucher->getKey()] = $voucher;

        $this->session->set('vouchers', $this->vouchers);

        return $this;
    }

    /**
     * @param ShippingOption $shippingOption
     * @return Cart
     */
    public function setShippingOption(ShippingOption $shippingOption)
    {
        $this->shippingOption = $shippingOption;

        $this->session->set('shipping_option', $shippingOption);

        return $this;
    }

    /**
     * @return ShippingOption
     */
    public function getShippingOption()
    {
        return $this->shippingOption;
    }

    /**
     * @return ShippingOption[]
     */
    public function getAvailableShippingOptions()
    {
        return (new ShippingOptionsFactory($this))->getAll();
    }

    /**
     * @return float
     */
    public function getSubtotal()
    {
        return $this->total();
    }

    /**
     * @return float
     */
    public function getDiscountTotal()
    {
        $discount = 0;

        foreach ($this->vouchers as $voucher) {
            switch($voucher->type)
            {
                case VoucherType::AMOUNT :
                    $discount += $voucher->amount;
                    break;
                case VoucherType::PERCENT :
                    $discount += $this->getSubtotal() / 100 * $voucher->amount;
                    break;
            }
        }

        return $discount;
    }

    /**
     * @return float
     */
    public function getShippingTotal()
    {
        if($this->getShippingOption()===null)
        {
            return 0;
        }

        return $this->getShippingOption()->getPrice();
    }

    /**
     * Total amount with all discounts applied and shipping
     * @return float
     */
    public function getTotal()
    {
        return $this->getSubtotal() - $this->getDiscountTotal() + $this->getShippingTotal();
    }

    /**
     * Check whether all products in stock
     * @return bool
     */
    public function isEverythingInStock()
    {
        foreach ($this->all() as $item) {
            $productVariation = ProductVariation::find($item['sku']);

            if($productVariation===null)
            {
                return false;
            }

            if($productVariation->stock < $item['quantity'])
            {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $fields
     *
     * @throws \InvalidArgumentException
     */
    protected function validate($fields)
    {
        if( isset($fields['weight']) )
        {
            if (!preg_match('/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/', $fields['weight']))
            {
                throw new \InvalidArgumentException('Invalid Weight for the item');
            }

            unset($fields['weight']);
        }

        parent::validate($fields);
    }

    public function update($id, $quantity)
    {
        parent::update($id, $quantity);

        // Check shipping method

        /** @var ShippingOption[] $options */
        $options = $this->getAvailableShippingOptions();

        /** @var ShippingOption $current */
        $current = $this->getShippingOption();

        if( ! isset($options[$current->getName()]) )
        {
            $this->setShippingOption(array_values($options)[0]);
        }
    }
}