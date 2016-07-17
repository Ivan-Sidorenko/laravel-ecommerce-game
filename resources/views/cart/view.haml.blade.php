@extends('app')

@section('content')
.row.hidden-xs.hidden-sm
  .col-lg-4.col-md-4
    %a{:href => route('store'), :class => "btn btn-default btn-store"}
      %i.fa.fa-undo
      Go Back Shopping
  .col-lg-4.col-md-4
    %h1
      YOUR SHOPPING BASKET
  .col-lg-4.col-md-4
    %h4
      Need Help: Call 0800 372 833
.row.hidden-lg.hidden-md
  .col-xs-12.col-sm-12
    %h1.text-center
      YOUR SHOPPING BASKET
.row.hidden-lg.hidden-md
  .col-xs-12.col-sm-12
    %h5.text-center
      Need Help? Call 0800 372 833
.row.hidden-lg.hidden-md
  .col-lg-12.col-md-12
    %h4
      You Have
      -echo $cart->totalItems()
      -echo str_plural('Item', $cart->totalItems())
      In Your Basket
%br.hidden-xs.hidden-sm
.row.hidden-xs.hidden-sm
  .col-lg-12.col-md-12
    %h2
      YOU HAVE
      -echo $cart->totalItems()
      -echo str_plural('ITEM', $cart->totalItems())
      IN YOUR BASKET
%br.hidden-xs.hidden-sm
-if($cart->isEmpty())
  .text-center
    %a{:href => route('store'), :class => "btn btn-default btn-store"}
      %i.fa.fa-undo
        Go Back Shopping
  %br/
-else
  .row.cart-container
    .row.hidden-xs.hidden-sm
      .col-lg-1.col-md-1
        %span.header
          &nbsp;
      .col-lg-4.col-md-4
        %span.header
          Name
      .col-lg-2.col-md-2
        %span.header
          Brand
      .col-lg-1.col-md-1
        %span.header
          Size
      .col-lg-1.col-md-1
        %span.header
          Price
      .col-lg-1.col-md-1
        %span.header
          Quantity
      .col-lg-1.col-md-1
        %span.header
          Total
      .col-lg-1.col-md-1
        %span.header
          &nbsp;
    -foreach($cart->all() as $item)
      -$v = \App\ProductVariation::find($item['sku'])
      %hr
      %form.form-inline{:method=>'post', :action=>route('cart.update')}
        -echo(csrf_field())
        .row.cart-item.hidden-xs.hidden-sm.vertical-align
          .col-lg-1.col-md-1
            %a{:href=>$v->view_product_url}
              %img{:src => $v->image_url, :class => "img-responsive"}
          .col-lg-4.col-md-4
            %a{:href=>$v->view_product_url}
              =$v->product->name
          .col-lg-2.col-md-2
            %a{:href=>"#"}
              =$v->product->brand->name
          .col-lg-1.col-md-1
            %a{:href=>"#"}
              -if($v->weight<=999)
                -echo $v->weight . 'g'
              -else
                -echo number_format($v->weight/1000, 1) . 'kg'
          .col-lg-1.col-md-1
            =sprintf("£%4.2f", $item['price'])
          .col-lg-1.col-md-1
            %input.text-center{:type=>'number', :min=>1, :class => "items_quantity", :name=>sprintf("items[%s]", $item['id']), :value=>$item['quantity']}
          .col-lg-1.col-md-1
            =sprintf("£%4.2f", $item['price']*$item['quantity'])
          .col-lg-1.col-md-1
            %a{:href=>"javascript:void(0);"}
              %i.fa.fa-times-circle
      .collapse-group
        %form.text-left{:method=>'post', :action=>route('cart.update')}
          -echo(csrf_field())
          .row.hidden-md.hidden-lg.collapse.in
            .col-xs-8.col-sm-8
              .row.vertical-align
                .col-xs-4.col-sm-4
                  Product
                .col-xs-8.col-sm-8
                  %a{:href=>$v->view_product_url}
                    =$v->product->name
              .row.quantity.vertical-align
                .col-xs-4.col-sm-4
                  %span
                    Quantity
                .col-xs-8.col-sm-8
                  %input.text-center{:type=>'number', :min=>1, :class => "items_quantity", :name=>sprintf("items[%s]", $item['id']), :value=>$item['quantity']}
                  %a{:href=>"javascript:void(0);"}
                    %i.fa.fa-times-circle
              .row
                .col-xs-12.col-sm-12
                  %h4
                    Total
                    =sprintf("£%4.2f", $item['price']*$item['quantity'])
            .col-xs-4.col-sm-4
              %a{:href=>$v->view_product_url}
                %img.img-responsive{:src => $v->image_url}
        %form.text-left{:method=>'post', :action=>route('cart.update')}
          -echo(csrf_field())
          .row.hidden-md.hidden-lg.collapse
            .col-xs-8.col-sm-8
              .row.vertical-align
                .col-xs-4.col-sm-4
                  Product
                .col-xs-8.col-sm-8
                  %a{:href=>$v->view_product_url}
                    =$v->product->name
              .row.vertical-align
                .col-xs-4.col-sm-4
                  Brand
                .col-xs-8.col-sm-8
                  %a{:href=>"#"}
                    =$v->product->brand->name
              .row.vertical-align
                .col-xs-4.col-sm-4
                  Size
                .col-xs-8.col-sm-8
                  %a{:href=>"#"}
                    =$v->size
              .row.vertical-align
                .col-xs-4.col-sm-4
                  Weight
                .col-xs-8.col-sm-8
                  %a{:href=>"#"}
                    -if($v->weight<=999)
                      -echo $v->weight . 'g'
                    -else
                      -echo number_format($v->weight/1000, 1) . 'kg'
              -if($v->flavor!==null)
                .row.vertical-align
                  .col-xs-4.col-sm-4
                    Flavour
                  .col-xs-8.col-sm-8
                    %a{:href=>"#"}
                      =$v->flavor->name
              .row.vertical-align
                .col-xs-4.col-sm-4
                  Price
                .col-xs-8.col-sm-8
                  =sprintf("£%4.2f", $item['price'])
              .row.quantity.vertical-align
                .col-xs-4.col-sm-4
                  %span
                    Quantity
                .col-xs-8.col-sm-8
                  %input.text-center{:type=>'number', :min=>1, :class => "items_quantity", :name=>sprintf("items[%s]", $item['id']), :value=>$item['quantity']}
                  %a{:href=>"javascript:void(0);"}
                    %i.fa.fa-times-circle
              %br/
              %br/
            .col-xs-4.col-sm-4
              %a{:href=>$v->view_product_url}
                %img.img-responsive{:src => $v->image_url}
              %br/
              %h4
                Total
                =sprintf("£%4.2f", $item['price']*$item['quantity'])
          .row.hidden-md.hidden-lg
            .col-xs-12.col-sm-12.text-center
              %a.view-details{:href=>"#"}
                See More Details
                %br
                %i.fa.fa-caret-down
              %a.hide-details{:href=>"#"}
                %i.fa.fa-caret-up
                %br
                Hide Details
  .row.hidden-xs.hidden-sm
    .col-lg-6.col-md-6
      %form{:method=>'post', :action=>route('cart.shipping')}
        -echo(csrf_field())
        %table.table.table-striped
          %tr
            %td{:colspan=>"4"}
              %h4
                Shipping Options
          -foreach($cart->getAvailableShippingOptions() as $shippingOption)
            %tr
              %td
                %h4
                  =$shippingOption->getName()
              %td.text-left
                !=$shippingOption->getDescription()
              %td
                =sprintf("£%4.2f", $shippingOption->getPrice())
              %td
                -$checked=false
                -if($cart->getShippingOption()!==null)
                  -if($shippingOption->getName() === $cart->getShippingOption()->getName())
                    -$checked=true
                -if($checked)
                  %input{:type=>"radio", :required=>"required", :checked=>"checked", :class=>"shipping_method", :name=>"shipping_method", :value=>$shippingOption->getName()}
                -else
                  %input{:type=>"radio", :required=>"required", :class=>"shipping_method", :name=>"shipping_method", :value=>$shippingOption->getName()}
    .col-lg-6.col-md-6
      .row
        .col-lg-10.col-md-10
          %h4.summary
            Sub Total
        .col-lg-2.col-md-2
          =sprintf("£%4.2f", $cart->getSubtotal())
      .row
        .col-lg-10.col-md-10
          %h4.summary
            Shipping
        .col-lg-2.col-md-2
          =sprintf("£%4.2f", $cart->getShippingTotal())
      -if($cart->getDiscountTotal() > 0)
        .row
          .col-lg-10.col-md-10
            %h4.summary
              Discount
          .col-lg-2.col-md-2
            =sprintf("-£%4.2f", $cart->getDiscountTotal())
      .row
        .col-lg-10.col-md-10
          %h4.summary
            Total Price
        .col-lg-2.col-md-2.text-bold
          =sprintf("£%4.2f", $cart->getTotal())
      .text-center
        %form{:method=>'post', :action=>route('checkout.proceed')}
          -echo(csrf_field())
          .checkbox
            %label
              %input.checkbox{:type=>"checkbox", :name=>"tos", :required=>"required", :value=>"yes"}
              I have read & agree to the
              %a{:href=>"#"}
                terms and conditions
          %button.btn.btn-default.btn-checkout
            PROCEED TO CHECKOUT
          %br/
          %br/
  .row.hidden-lg.hidden-md
    %form.collapse-group{:id=>"shipping_accordion",:method=>'post', :action=>route('cart.shipping')}
      -echo(csrf_field())
      .row.text-center.shipping_header{"data-toggle"=>"collapse", "data-parent"=>"#shipping_accordion", :href=>"#collapseOne", "aria-expanded"=>"true", "aria-controls"=>"collapseOne"}
        .col-xs-3.col-sm-3
          open
          %br/
          %i.fa.fa-caret-down
        .col-xs-6.col-sm-6.bold
          Choose Delivery Method
        .col-xs-3.col-sm-3
          open
          %br/
          %i.fa.fa-caret-down
      .row.collapse.row-striped{:id=>"collapseOne"}
        .col-xs-12.col-sm-12
          -foreach($cart->getAvailableShippingOptions() as $shippingOption)
            .row
              %h4.text-center
                =$shippingOption->getName()
              .col-xs-8.col-sm-8
                !=$shippingOption->getDescription()
              .col-xs-2.col-sm-2
                =sprintf("£%4.2f", $shippingOption->getPrice())
              .col-xs-2.col-sm-2
                -$checked=false
                -if($cart->getShippingOption()!==null)
                  -if($shippingOption->getName() === $cart->getShippingOption()->getName())
                    -$checked=true
                -if($checked)
                  %input{:type=>"radio", :required=>"required", :checked=>"checked", :class=>"shipping_method", :name=>"shipping_method", :value=>$shippingOption->getName()}
                -else
                  %input{:type=>"radio", :required=>"required", :class=>"shipping_method", :name=>"shipping_method", :value=>$shippingOption->getName()}
  .row.hidden-lg.hidden-md
    %br/
    .row
      .col-xs-8.col-sm-8
        %h4.summary
          Sub Total
      .col-xs-4.col-sm-4
        =sprintf("£%4.2f", $cart->getSubtotal())
    .row
      .col-xs-8.col-sm-8
        %h4.summary
          Shipping
      .col-xs-4.col-sm-4
        =sprintf("£%4.2f", $cart->getShippingTotal())
    -if($cart->getDiscountTotal() > 0)
      .row
        .col-xs-8.col-sm-8
          %h4.summary
            Discount
        .col-xs-4.col-sm-4
          =sprintf("-£%4.2f", $cart->getDiscountTotal())
    .row
      .col-xs-8.col-sm-8
        %h4.summary
          Total Price
      .col-xs-4.col-sm-4.text-bold
        =sprintf("£%4.2f", $cart->getTotal())
    .row
      .col-xs-12.col-sm-12.text-center
        %hr
        %form{:method=>'post', :action=>route('cart.voucher')}
          -echo(csrf_field())
          %h4.voucher
            Voucher
            %input.form-control{:type=>"text", :name=>"voucher", :placeholder=>"Input Code"}
            %button.btn.btn-default.btn-store{:type=>"submit"}
              Apply
        %hr
    .text-center
      %form{:method=>'post', :action=>URL::route('checkout.proceed')}
        -echo(csrf_field())
        .checkbox
          %label
            %input.checkbox{:type=>"checkbox", :name=>"tos", :required=>"required", :value=>"yes"}
            I have read & agree to the
            %a{:href=>"#"}
              terms and conditions
        %button.btn.btn-default.btn-checkout
          PROCEED TO CHECKOUT
        %br/
        %br/
  .row.hidden-xs.hidden-sm
    .col-lg-12.col-md-12.text-center
      %hr
      %form{:method=>'post', :action=>route('cart.voucher')}
        -echo(csrf_field())
        %h4.voucher
          Voucher Code
          %input.form-control{:type=>"text", :name=>"voucher", :placeholder=>"Input Code Here"}
          %button.btn.btn-default.btn-store{:type=>"submit"}
            Apply
      %hr
  %br/
@stop
