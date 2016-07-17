<?php

namespace App\Classes\Cart;

/**
 * ShippingOptionsFactory
 *
 * @author Alexander Begoon <alexander.begoon@gmail.com>
 */
class ShippingOptionsFactory
{
    const EXPRESS = 'Express Delivery';
    const NEXT_DAY = 'Next Day Delivery';

    /**
     * @var Cart
     */
    private $cart;

    /**
     * @var ShippingOption[]
     */
    private $shippingOptions = [];

    /**
     * ShippingOptionsFactory constructor.
     * @param Cart $cart
     */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;

        $this->create();
    }

    /**
     * @return ShippingOption[]
     */
    public function getAll()
    {
        return $this->shippingOptions;
    }

    private function create()
    {
        $option = new ShippingOption();

        $option->setName(self::EXPRESS);
        $option->setDescription('<ul><li>Delivered in 2-3 Working days</li><li>Free on all orders over £49</li></ul>');
        $option->setPrice(0);

        $this->setShippingOption($option);

        if($this->cart->totalWeight() < 2000 && $this->cart->isEverythingInStock() === true)
        {
            $option = new ShippingOption();

            $option->setName(self::NEXT_DAY)
            ->setDescription('<ul><li>Delivered tomorrow before 1 p.m.</li><li>Free on all orders over £149</li></ul>')
            ->setPrice(2.99);

            $this->setShippingOption($option);
        }
    }

    /**
     * @param ShippingOption $shippingOption
     * @return $this
     */
    private function setShippingOption(ShippingOption $shippingOption)
    {
        $this->shippingOptions[$shippingOption->getName()] = $shippingOption;

        return $this;
    }
}