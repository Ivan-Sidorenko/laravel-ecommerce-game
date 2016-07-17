<?php

namespace tests;
use App\Classes\Cart\ShippingOptionsFactory;
use App\ProductVariation;
use TestCase;

/**
 * CartControllerTest
 *
 * @author Alexander Begoon <alexander.begoon@gmail.com>
 */
class CartControllerTest extends TestCase
{
    public function testAddItem()
    {
        $productVariation = ProductVariation::first();

        $this->get('cart/add/'.$productVariation->id);

        $this->assertResponseStatus(302);

        $this->assertSessionHas('success');

        $this->assertRedirectedToRoute('cart.view');
    }

    public function testView()
    {
        $this->get('cart/view/');

        $this->assertResponseOk();
    }

    public function testUpdate()
    {
        $productVariation = ProductVariation::first();

        $postData = [
            'items'=>[$productVariation->id => 5],
            '_token' => csrf_token()
        ];

        $this->post('cart/update', $postData);

        $this->assertRedirectedToRoute('cart.view');
        $this->assertSessionHas('success');
    }

    public function testShippingMethod()
    {
        $shippingMethod = ShippingOptionsFactory::EXPRESS;

        $postData = [
            'shipping_method'=>$shippingMethod,
            '_token' => csrf_token()
        ];

        $this->post('cart/shipping', $postData);

        $this->assertRedirectedToRoute('cart.view');
        $this->assertSessionHas('success');
    }
}