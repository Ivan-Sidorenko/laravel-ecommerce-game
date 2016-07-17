<?php

namespace App\Classes\Cart;

use App\Voucher;

/**
 * CartInterface
 *
 * @author Alexander Begoon <alexander.begoon@gmail.com>
 */
interface CartInterface
{
    /**
     * @return float
     */
    public function totalWeight();

    /**
     * @param Voucher $voucher
     * @return Cart
     */
    public function applyVoucher(Voucher $voucher);

    /**
     * @param ShippingOption $shippingOption
     * @return Cart
     */
    public function setShippingOption(ShippingOption $shippingOption);

    /**
     * @return ShippingOption
     */
    public function getShippingOption();

    /**
     * @return ShippingOption[]
     */
    public function getAvailableShippingOptions();

    /**
     * @return float
     */
    public function getSubtotal();

    /**
     * @return float
     */
    public function getDiscountTotal();

    /**
     * @return float
     */
    public function getShippingTotal();

    /**
     * Total amount with all discounts applied and shipping
     * @return float
     */
    public function getTotal();

    /**
     * Check whether all products in stock
     * @return bool
     */
    public function isEverythingInStock();
}