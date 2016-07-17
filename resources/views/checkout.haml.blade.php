@extends('app')

@section('content')
.container
  .order
    %h1{:style => "text-align:center;"}
      Secure Order Form
    -if (count($errors) > 0)
      .alert.alert-danger
        %ul
          -foreach($errors->all() as $error)
            %li =$error
    -if(session()->has('reason'))
      .alert.alert-danger
        =session('reason')
    .row
      .col-xs-12
        -$order_type = old('order_type', Input::get('t'))
        %form{:action=>route('checkout.do'), :method=>'post'}
          - echo csrf_field()
          %input{:type=>'hidden', :name=>'order_type', :value=>$order_type}
          .row
            .col-xs-12.col-sm-6
              %h2 Billing Address
              -foreach($fields as $k=>$v)
                -$id = "billing_{$k}"
                .form-group
                  %input.form-control{:id=>$id, :name=>$id, :placeholder => $v['label'], :type => "text", :value => old($id)}
              .form-group
                %input#email.form-control{:name=>'email', :placeholder => "Email", :type => "text", :value=>old('email')}
              .form-group
                %input#phone.form-control{:name=>'phone', :placeholder => "Phone (numbers only)", :type => "text", :value=>old('phone')}
              %h2 Shipping Address
              .form-group
                .checkbox
                  %label
                    %input#same_as_billing{:type => "checkbox", :name=>'same_as_billing', :checked=>old('same_as_billing'), :value=>1}
                    Same as billing
                    :javascript
                      $(function() {
                        $('#same_as_billing').change(function() {
                          $('#shipping').toggleClass('hidden');
                        });
                      });
              #shipping{:class=>old('same_as_billing') ? 'hidden' : ''}
                -foreach($fields as $k=>$v)
                  -$id = "shipping_{$k}"
                  .form-group
                    %input.form-control{:id=>$id, :name=>$id, :placeholder => $v['label'], :type => "text", :value => old($id)}
            .col-xs-12.col-sm-6
              %h2 Your Order
              %table.table
                %tr
                  %th Product
                  %th Qty
                  %th Subtotal
                -foreach($cart->all() as $item)
                  -$v = \App\ProductVariation::find($item['sku'])
                  %tr
                    %td
                      %a{:href=>$v->view_product_url}
                        =$v->product->name
                        =$v->summary(false)
                    %td
                      =$item['quantity']
                    %td
                      =sprintf("£%4.2f", $item['price']*$item['quantity'])
                %tr.total
                  %th TOTAL
                  %td
                  %th
                    =sprintf("£%4.2f", $cart->total())
                  
              %h2 Payment Information
              %p
                Secured by SagePay
              .form-group
                %input#cc_num.form-control{:name=>'cc_num', :placeholder => "Card Number (numbers only)", :type => "text", :value=>old('cc_num')}
              .form-group
                %input#cc_exp.form-control{:name=>'cc_exp', :placeholder => "Expiration (MM/YYYY)", :type => "text", :value=>old('cc_exp')}
              .form-group
                %input#cc_cvv.form-control{:name=>'cc_cvv', :placeholder => "CVV", :type => "text", :value=>old('cc_cvv')}
              .text-center
                %button.btn.btn-default{:type => "submit"} Pay Now
@endsection