@extends('app')
@section('head')

%link{:href => "http://css-spinners.com/css/spinner/spinner.css", :rel => "stylesheet", :type => "text/css"}
@endsection
@section('content')

.container
  .row.margin-bottom-20
    .col-md-3.col-sm-3.col-xs-5
      %img{:src => "/images/logos/sagepay.png"}/
    .col-md-7.col-xs-12.col-sm-7.text-center
      %h3.sagePay-OM MVM
      %h3.display-inline-block CHECKOUT
    .col-md-2.col-sm-2.securedHeader.col-xs-4
      %img.securedJpg{:src => "/images/logos/secured.png"}
  .row.margin-bottom-20
    .col-md-12
      .col-md-12.product-list
        .row
          .col-md-6.border-right-1
            #table_product_list_table.table-responsive
              %table#product_list_table.table
                -$totalPrice = 0
                -foreach($cart as $key =>$value)
                  -$totalPrice = $totalPrice + $value['price']* $value['quantity']
                  %tr
                    %td.width-2
                      -$id="product{$key}"
                      -$quantity = "quantity{$key}"
                      %input{:id =>$id, :name =>$id, :type => "hidden", :value => $value['id']}
                      %input{:id =>$quantity, :name =>$quantity, :type => "hidden", :value => $value['quantity']}
                    %td.img-width-50
                      -$url = "/products/{$cart_product[$key]['product']->product->slug}?vid={$value['sku']}"
                      -$imageUrl =  $cart_product[$key]['product']->getImageUrlAttribute()
                      %a{:href =>$url, :target => "_blank"}
                        %img.img-responsive{:src => $imageUrl}
                    %td.font-color-change
                      %a{:href =>$url, :target => "_blank"}
                        = $value['description']
                    %td.font-color-change
                      %a{:href =>$url, :target => "_blank"}
                        = $value['quantity']
                    %td.font-color-change
                      %a{:href =>$url, :target => "_blank"}
                        = $cart_product[$key]['product']->size
          .col-md-4.col-md-offset-1.text-center
            .row
              .col-md-12
                #totalID
                  %span.font-color-change.font-size-25 Total Price
                  %span.vertical-align-middle.margin-left-10.font-size-25
                    -$price= "{$totalPrice}"
                    =sprintf("£", "&pound")
                    = $price
  .row.margin-bottom-20
    .col-md-9
      %button#step1_button.btn.btn-primary.checkout-step-header{"data-target" => "#step1", "data-toggle" => "collapse", :type => "button"}
        Step 1 : New Or Returning Customer
        %i.fa.fa-check.i_checked.step1_i_check.font-size-25
      #step1.step-body.collapse.in.margin-bottom-20
        .row
          .col-md-6
            %h4.text-center.am-new-customer I AM  A NEW CUSTOMER
            .row
              .col-md-12.padding-top-35.padding-bottom-58.border-right-1.new-customer-body
                .row.margin-bottom-20
                  .col-md-6
                    %i.fa.fa-check.font-size-11
                    %span.font-size-11 Secure & Simple Checkout
                  .col-md-6
                    %i.fa.fa-check.font-size-11
                    %span.font-size-11 All major Payment type accepted
                .row
                  .col-md-6
                    %i.fa.fa-check.font-size-11
                    %span.font-size-11 See your checkout progress
                  .col-md-6
                    %i.fa.fa-check.font-size-11
                    %span.font-size-11 We safeguard your information
            .row
              .col-md-12.text-center.margin-bottom-20
                %a.btn.btn-success.border-radius-0{:href => "javascript:void(0)", :onclick => "onGoToAddressBefore()"} Proceed to Address
          .col-md-6
            %h4.text-center.am-new-member I AM  A MEMBER
            .row
              .col-md-12.padding-top-35.padding-bottom-20
                .row
                  .col-md-6.border-right-1
                    %form#emailForm{:action => URL::route('checkout.emailLogin'), :method => "post"}
                      %input{:name => "_token", :type => "hidden", :value =>  csrf_token() }
                      -foreach([ 'email' => 'Email','password' => 'Password'] as $key =>$value)
                        .form-group
                          - if ($key === 'email')
                            %input#email.form-control{:name => $key, :placeholder => "Email", :type => "email"}
                          - else
                            %input#password.form-control{:name => $key, :placeholder => "Password", :type => "password"}
                  .col-md-6
                    %p.text-center Or Login In With
                    .row
                      .col-md-12.text-center
                        %a{:href => ""}
                          %img.login-facebook{:src => "/images/logos/facebook.png"}
                        %a{:href => ""}
                          %img.login-facebook{:src => "/images/logos/twitter.png"}
                        %a{:href => ""}
                          %img.login-facebook{:src => "/images/logos/google.png"}
            .row
              .col-md-12.text-center
                %a.btn.btn-success.border-radius-0{:href => "javascript:void(0)", :onclick => "onGoToPaymentFirst()"} Proceed to Payment
      %button#step2_button.btn.checkout-step-disable-button.checkout-step-header.margin-top-20{"data-target" => "#step2", :type => "button"}
        Step 2 : Address
        %i.fa.fa-check.i_checked.step2_i_check.font-size-25
      #step2.step-body.collapse.margin-bottom-20
        .row.padding-top-20
          .col-12.margin-bottom-20
            %form#addressForm.form-horizontal.left-form-horizontal{:action => URL::route('checkout.address'), :method => "post"}
              %input{:name => "_token", :type => "hidden", :value => csrf_token()}
              %input{:name => "return", :type => "hidden", :value => 0, :id => "returnHidden"}
              .row.margin-bottom-20
                .col-md-6
                  %input{:name => "payment_value", :type => "hidden", :value => $totalPrice}
                  %input#user_ID{:name => "userID", :type => "hidden"}
                  -foreach([ 'first_name' => 'First Name', 'last_name' => 'Last Name','address' => 'Address','city' => 'City','state' => 'State'] as $key =>$value)
                    .form-group
                      %label.col-md-4.control-label
                        - if($key !=='state')
                          %span.color-red *
                        = $value
                      .col-md-8
                        -if($key === 'country')
                          %select.form-control{:name => $key}
                            %option{:value =>"" } Select Country
                            -foreach($countries as $key=>$value)
                              %option{:value => $value->country_short_name}  = $value->country_long_name
                        -else
                          %input.form-control{:name => $key, :placeholder => $value, :type => "text"}
                .col-md-6
                  -foreach(['country' => 'Country','postal_code' => 'Postal Code','phone_number' => 'Phone Number','email' => 'Email'] as $key =>$value)
                    .form-group
                      %label.col-md-4.control-label
                        - if($key === 'email')
                          %span.color-red *
                        = $value
                      .col-md-8
                        -if($key === 'country')
                          %select.form-control{:name => $key}
                            %option{:value =>"" } Select Country
                            -foreach($countries as $key=>$value)
                              %option{:value => $value->country_short_name}  = $value->country_long_name
                        -else
                          %input.form-control{:name => $key, :placeholder => $value, :type => "text"}
                  .form-group
                    .col-md-12.margin-bottom-10
                      %input#deliveryRadio0{:checked => "", :name => "delivery", :onchange => "onChangeDelivery()", :type => "radio", :value => "0"}
                        Delivery address is the
                        %span the same
                        as billing address
                    .col-md-12
                      %input#deliveryRadio1{:name => "delivery", :onchange => "onChangeDelivery()", :type => "radio", :value => "1"}
                        Delivery address is the
                        %span different
                        as billing address
              #delivery_form_list.row
                .col-md-6
                  - foreach([ 'delivery_first_name' => 'First Name','delivery_last_name' => 'Last Name','delivery_address' => 'Address', 'delivery_city' => 'City'] as $key =>$value)
                    .form-group
                      %label.control-label.col-md-4
                        - if($key !=='delivery_state')
                          %span.color-red *
                        = $value
                      .col-md-8
                        %input.form-control{:name => $key, :placeholder => $value, :type => "text"}
                .col-md-6
                  -foreach(['delivery_state' => 'State','delivery_country' => 'Country','delivery_postal_code' => 'Postal Code']   as $key =>$value)
                    .form-group
                      %label.control-label.col-md-4
                        %span.color-red *
                        = $value
                      .col-md-8
                        -if($key === 'delivery_country')
                          %select.form-control{:name => $key}
                            %option{:value =>"" } Select Country
                            -foreach($countries as $key=>$value)
                              %option{:value => $value->country_short_name}  = $value->country_long_name
                        -else
                          %input.form-control{:name => $key, :placeholder => $value, :type => "text"}
          .col-md-12.text-center
            %a.btn.btn-success.border-radius-0{:href => "javascript:void(0)", :onclick => "onGoToPaymentSecond()"} Proceed to Payment
      %button#step3_button.btn.checkout-step-disable-button.checkout-step-header.margin-top-20{"data-target" => "#step3", :type => "button"}
        Step 3 : Payment
        %i.fa.fa-check.i_checked.step3_i_check.font-size-25
      #step3.step-body.collapse.margin-bottom-20
        .row.padding-top-20
          .col-12.margin-bottom-20.padding-bottom-20.border-bottom-1
            .row
              .col-md-4.text-center
                %input.vertical-align-middle{:checked => "", :name => "paymentMethod", :onchange => "onChangePaymentCheck()", :type => "radio", :value => "visa"}
                  %span.vertical-align-bottom Visa
                  %img#visa{:src => "/images/logos/visa.png"}
              .col-md-4.text-center
                %input.vertical-align-middle{:name => "paymentMethod", :onchange => "onChangePaymentCheck()", :type => "radio", :value => "card"}
                  %span.vertical-align-bottom Master Card
                  %img#visa{:src => "/images/logos/master_card.png"}
              .col-md-4.text-center
                %input.vertical-align-middle{:name => "paymentMethod", :onchange => "onChangePaymentCheck()", :type => "radio", :value => "paypal"}
                  %span.vertical-align-bottom Paypal
                  %img#visa{:src => "/images/logos/paypal.png"}
          .col-md-12
            %form#doPaypalFrom.form-horizontal{:action => URL::route('checkout.do.paypal'),  :method => "post"}
              %input{:name => "_token", :type => "hidden", :value => csrf_token()}
              %input{:name => "payment_value", :type => "hidden", :value => $totalPrice}
            %form#paypalForm.form-horizontal{:action => "https://www.sandbox.paypal.com/cgi-bin/webscr", :method => "post"}
              .form-group
                .row
                  .col-md-12
                    %input{:name => "cmd", :type => "hidden", :value => "_xclick"}
                    %input{:name => "item_name", :type => "hidden", :value => "MVM Checkout"}
                    %input{:name => "amount", :type => "hidden", :value => $totalPrice}
                    %input{:name => "business", :type => "hidden", :value => "jeni.star90@yahoo.com"}
                    %input{:name => "lc", :type => "hidden", :value => "GBP"}
                    %input{:name => "currency_code", :type => "hidden", :value => "GBP"}
                    %input{:name => "page_stype", :type => "hidden", :value => "primary"}
                    %input{:name => "bn", :type => "hidden", :value =>"PP-BuyNowBF"}
                    %input{:name => "no_shipping", :type => "hidden", :value => "0"}
                    %input{:name => "no_note", :type => "hidden", :value => "1"}
                    %input{:name => "custom",  :type => "hidden", :value => "1", :id => "custom"}
                    %input{:name => "cancel_return", :type =>"hidden", :value => URL::route('checkout')}
                    %input{:name => "return", :type =>"hidden", :value => URL::route('checkout')}
                    %input{:name => "notify_url", :type =>"hidden", :value => URL::route('checkout.paypalNotify')}

            %form#cardForm.form-horizontal{:action => URL::route('checkout.do.new.address'), :method => "post"}
              %input{:name => "_token", :type => "hidden", :value => csrf_token()}
              %input{:name => "payment_value", :type => "hidden", :value => $totalPrice}
              .row
                .col-md-6
                  .form-group
                    %label.control-label.col-md-4 Name on card
                    .col-md-8
                      %input.form-control{:name => "name_on_card", :placeholder => "Name on card", :type => "text"}
                  .form-group
                    %label.control-label.col-md-4 Card Number
                    .col-md-8
                      %input.form-control{:name => "card_number", :placeholder => "Card Number", :type => "text"}
                .col-md-4
                  .form-group
                    %label.control-label.col-md-6 Expiration  date
                    .col-md-6
                      %input.form-control{:name => "expiration_date", :placeholder => "MM", :type => "text"}
                  .form-group
                    %label.control-label.col-md-6 Card vercation  Number(csv)
                    .col-md-6
                      %input.form-control{:name => "csv", :placeholder => "Card vercation  Number", :type => "password"}
                .col-md-2
                  %input.form-control{:name => "expiration_year", :placeholder => "YYYY", :type => "text"}

          .col-md-12
            .row
              .col-md-6.col-md-offset-6.text-center
                -$billingID  = Session::get('userID')
                -if(Session::get('userID') == 0)
                %a#completePaymentButtonContent.btn.btn-success.border-radius-0{:href => "javascript:void(0)", :onclick => "onCompletePayment()"}
                  Complete payment
                .spinner-loader
                %a#checkoutWithPaypalButtonContent.btn.btn-success.border-radius-0{:href => "javascript:void(0)", :onclick => "onCheckoutPayment()"}
                  Checkout with paypal
      .col-md-12.margin-bottom-25
    .col-md-3.checkout-step3-right-body
      %p.checkout-step3-right-header.margin-bottom-20 CHECKOUT PROGRESS
      %button#new_or_returning_button.btn.checkout-step3-button.margin-bottom-20.width-100.text-left.color-white{:type => "button"}
        New Or Returning
        %i.fa.fa-check.i_checked.step1_i_check
      %button#address_button.btn.checkout-step-disable-button.margin-bottom-20.width-100.text-left.color-white{:type => "button", :onclick => "onReturnAddress()"}
        Address
        %i.fa.fa-check.i_checked.step2_i_check
      %button#payment_method_button.btn.checkout-step-disable-button.margin-bottom-20.width-100.text-left.color-white{:type => "button"}
        Payment Method
        %i.fa.fa-check.i_checked.step3_i_check
    #myModal.modal.fade{:role => "dialog"}
      .modal-dialog.modal-lg
        .modal-content
          .modal-header
            %button.close{"data-dismiss" => "modal", :type => "button"} &times;
            %h4.modal-title Address
          .modal-body
            %form#modalAddressForm.form-horizontal.left-form-horizontal{:action => URL::route('checkout.address'), :method => "post"}
              %input{:name => "_token", :type => "hidden", :value =>  csrf_token()}
              %input#user_ID{:name => "userID", :type => "hidden"}
              %input{:name => "payment_value", :type => "hidden", :value => $totalPrice}
              .row.margin-bottom-20
                .col-md-6
                  -foreach([ 'first_name' => 'First Name','last_name' => 'Last Name','address' => 'Address','city' => 'City','state' => 'State','country' => 'Country','postal_code' => 'Postal Code'] as $key =>$value)
                    .form-group
                      %label.col-md-4.control-label
                        - if($key !=='state')
                          %span.color-red *
                        = $value
                      .col-md-8
                        -if($key === 'country')
                          %select.form-control{:name => $key}
                            %option{:value =>"" } Select Country
                            -foreach($countries as $key=>$value)
                              %option{:value => $value->country_short_name}  = $value->country_long_name
                        -else
                          %input.form-control{:name => $key, :placeholder => $value, :type => "text"}
                .col-md-6
                  -foreach([ 'phone_number' => 'Phone Number','email' => 'Email', 'password' => 'Password','password_confirmation' => 'Confirm Password'] as $key =>$value)
                    .form-group
                      %label.col-md-4.control-label
                        - if($key === "email")
                          %span.color-red *
                        = $value
                      .col-md-8
                        - if($key ==="password" || $key === "password_confirmation")
                          %input.form-control{:name => $key, :placeholder => $value, :type => "password"}
                        -else
                          %input.form-control{:name => $key, :placeholder => $value, :type => "text"}
                  .form-group
                    .col-md-12.margin-bottom-10
                      %input{:checked => "", :name => "delivery", :onchange => "onChangeModalDelivery()", :type => "radio", :value => "0"}
                        Delivery address is the
                        %span the same
                        as billing address
                    .col-md-12
                      %input{:name => "delivery", :onchange => "onChangeModalDelivery()", :type => "radio", :value => "1"}
                        Delivery address is the
                        %span different
                        as billing address
              #delivery_modal_form_list.row
                .col-md-6
                  -foreach([ 'delivery_first_name' => 'First Name','delivery_last_name' => 'Last Name','delivery_address' => 'Address', 'delivery_city' => 'City'] as $key =>$value)
                    .form-group
                      %label.control-label.col-md-4
                        %span.color-red *
                        = $value
                      .col-md-8
                        %input.form-control{:name => $key, :placeholder => $value, :type => "text"}
                .col-md-6
                  -foreach(['delivery_state' => 'State','delivery_country' => 'Country','delivery_postal_code' => 'Postal Code']   as $key =>$value)
                    .form-group
                      %label.control-label.col-md-4
                        - if($key !=='delivery_state')
                          %span.color-red *
                        = $value
                      .col-md-8
                        -if($key === 'delivery_country')
                          %select.form-control{:name => $key}
                            %option{:value =>"" } Select Country
                            -foreach($countries as $key=>$value)
                              %option{:value => $value->country_short_name}  = $value->country_long_name
                        -else
                          %input.form-control{:name => $key, :placeholder => $value, :type => "text"}
          .modal-footer
            %a.btn.btn-success.border-radius-0{:href => "javascript:void(0)", :onclick => "onSaveModalAddress()"} Save
            %button.btn.btn-default{"data-dismiss" => "modal", :type => "button"} Close
    #signup.modal.fade{:role => "dialog"}
      .modal-dialog
        .modal-content
          .modal-header
            %button.close{"data-dismiss" => "modal", :type => "button"} &times;
            %h4.modal-title Sign Up
          .modal-body
            %form#signupForm.form-horizontal{:action => "", :method => "post"}
              %input{:name => "_token", :type => "hidden", :value =>  csrf_token()}
              -foreach(['name' => "Name", "email" => "Email", "password" => "Password", "password_confirmation" =>"Confirm Password"] as $key => $value)
                .form-group
                  %label.col-md-4.control-label
                    %span.color-red *
                    = $value
                  .col-md-8
                    -if($key ==="email")
                      %input.form-control{:name => $key, :type => "email", :placeholder => $value}
                    -else if($key === "name")
                      %input.form-control{:name => $key, :type => "text", :placeholder => $value}
                    -else
                      %input.form-control{:name => $key, :type => "password", :placeholder => $value}
          .modal-footer
            %a.btn.btn-success.border-radius-0{:href => "javascript:void(0)", :onclick => "onSaveModalSignup()"} Save
            %button.btn.btn-default{"data-dismiss" => "modal", :type => "button"} Close

%script{:src => "/assets/admin/js/jquery.form.js", :type => "text/javascript"}
%script{:src => "/assets/admin/js/bootbox.js", :type => "text/javascript"}
:javascript
  $( document ).ready(function() {
    var table_product_list_table= $("#table_product_list_table").height();
    var totalID = $("#totalID").height();
    var middelHeight = (table_product_list_table-totalID)/2;
    $("#totalID").css('padding-top',middelHeight);
  });
  function onSignUpForm(){
    $("#signup").modal("show");
  }
  function onSaveModalSignup(){
    $("#signupForm").ajaxForm({
      success:function(data){
      if(data.result == "success"){
        $("#signup").modal("hide");
        $("#addressForm").find("input#user_ID").val(data.userID);
        onGoToAddress();
      }else if(data.result == "failed"){
        var arr = data.error;
        var errorList = '';
        $.each(arr, function(index, value)
        {
          if (value.length != 0)
            {
              errorList = errorList + value;
            }
        });
        bootbox.alert(errorList);
       }
     }
    }).submit();
  }
  function onGoToAddressBefore(){
    $("#addressForm").find("input[name=first_name]").val("");
    $("#addressForm").find("input[name=last_name]").val("");
    $("#addressForm").find("input[name=address]").val("");
    $("#addressForm").find("input[name=city]").val("");
    $("#addressForm").find("input[name=state]").val("");
    $("#addressForm").find("select[name=country]").val("");
    $("#addressForm").find("input[name=postal_code]").val("");
    $("#addressForm").find("input[name=phone_number]").val("");
    $("#addressForm").find("input[name=email]").val("");
    $("#addressForm").find("#deliveryRadio0").prop('checked', true);
    $("#delivery_form_list").hide();
    $("#addressForm").find("input[name=delivery_first_name]").val("");
    $("#addressForm").find("input[name=delivery_last_name]").val("");
    $("#addressForm").find("input[name=delivery_address]").val("");
    $("#addressForm").find("input[name=delivery_state]").val("");
    $("#addressForm").find("input[name=delivery_city]").val("");
    $("#addressForm").find("select[name=delivery_country]").val("");
    $("#addressForm").find("input[name=delivery_postal_code]").val("");
    onGoToAddress();
  }
  function onGoToAddress(){
    $("#step1").removeClass('in');
    $("#step1_button").removeClass('btn-primary');
    $("#step1_button").addClass('checkout-step-check');
    $("#step1_button").removeAttr('data-toggle');
    $("#step2_button").add('data-toggle','collapse');
    $("#step2_button").removeClass('checkout-step-disable-button');
    $("#step2").addClass('in');
    $("#step2_button").addClass('btn-primary');
    $("#new_or_returning_button").removeClass('checkout-step3-button');
    $("#new_or_returning_button").addClass('checkout-step-check');
    $("#address_button").removeClass('checkout-step-disable-button');
    $("#address_button").addClass('checkout-step3-button');
    $(".step1_i_check").css('display','block');
  }
  function onGoToPaymentFirst(){
     $("#emailForm").ajaxForm({
       success:function(data){
         if(data.result == "success"){
           $("#addressForm").find("input[name=first_name]").val(data.addressList.first_name);
           $("#addressForm").find("input[name=last_name]").val(data.addressList.last_name);
           $("#addressForm").find("input[name=address]").val(data.addressList.address);
           $("#addressForm").find("input[name=city]").val(data.addressList.city);
           $("#addressForm").find("input[name=state]").val(data.addressList.state);
           $("#addressForm").find("select[name=country]").val(data.addressList.country);
           $("#addressForm").find("input[name=postal_code]").val(data.addressList.postal_code);
           $("#addressForm").find("input[name=phone_number]").val(data.addressList.phone_number);
           $("#addressForm").find("input[name=email]").val(data.addressList.email);
           $("#addressForm").find("input[name=email]").attr("readonly","true");
           if(data.addressList.delivery == 1){
             $("#addressForm").find("#deliveryRadio1").prop('checked', true);
               $("#delivery_form_list").show();
             }else{
               $("#addressForm").find("#deliveryRadio0").prop('checked', true);
             }

             if(data.addressList.delivery == 1){
             $("#addressForm").find("input[name=delivery_first_name]").val(data.addressList.delivery_first_name);
             $("#addressForm").find("input[name=delivery_last_name]").val(data.addressList.delivery_last_name);
             $("#addressForm").find("input[name=delivery_address]").val(data.addressList.delivery_address);
             $("#addressForm").find("input[name=delivery_state]").val(data.addressList.delivery_state);
             $("#addressForm").find("input[name=delivery_city]").val(data.addressList.delivery_city);
             $("#addressForm").find("select[name=delivery_country]").val(data.addressList.delivery_country);
             $("#addressForm").find("input[name=delivery_postal_code]").val(data.addressList.delivery_postal_code);
           }
           $("#addressForm").find("input#user_ID").val(data.userID);
           onGoToPayment();
         }else if(data.result == "failed"){
           var arr = data.error;
           var errorList = '';
           $.each(arr, function(index, value)
           {
             if (value.length != 0)
               {
                 errorList = errorList + value;
               }
           });
           bootbox.alert(errorList);
         }else if(data.result == "userEmpty"){
           bootbox.alert("User email or password is incorrect.");
         }else if(data.result == "billingEmpty"){
           $("#addressForm").find("input#user_ID").val(data.userID);
           onGoToAddress();
         }
       }
     }).submit();
  }
  function onChangeModalDelivery(){
    var  delivery_input=  $("#modalAddressForm").find("input[name=delivery]:checked").val();
    if(delivery_input == 1){
      $("#delivery_modal_form_list").show();
    }else{
      $("#delivery_modal_form_list").hide();
    }
  }
  function onChangeDelivery(){
    var  delivery_input=  $("#addressForm").find("input[name=delivery]:checked").val();
    if(delivery_input == 1){
      $("#delivery_form_list").show();
    }else{
      $("#delivery_form_list").hide();
    }
  }
  function onSaveModalAddress(){
    $("#modalAddressForm").ajaxForm({
      success:function(data){
        if(data.result == "success"){
          $("#myModal").modal('hide');
          onGoToPayment();
        }else if(data.result == "failed"){
          var arr = data.error;
          var errorList = '';
          $.each(arr, function(index, value)
          {
            if (value.length != 0)
            {
              errorList = errorList + value;
            }
          });
          bootbox.alert(errorList);
        }
      }
    }).submit();
  }
  function onGoToPaymentSecond(){
    $("#addressForm").ajaxForm({
      success:function(data){
        if(data.result == "success"){
          onGoToPayment();
        }else if(data.result == "failed"){
          var arr = data.error;
          var errorList = '';
          $.each(arr, function(index, value)
          {
            if (value.length != 0)
            {
              errorList = errorList + value;
            }
          });
          bootbox.alert(errorList);
        }
      }
    }).submit();
  }
  function onReturnAddress(){
    $("#step2").addClass('in');
    $("#step3").removeClass('in');
    $("#step2_button").removeClass('checkout-step-check');
    $("#step2_button").removeClass('checkout-step-disable-button');
    $("#step2_button").addClass('btn-primary');
    $("#step3_button").removeAttr('data-toggle');
    $("#step2_button").add('data-toggle','collapse');
    $("#step3_button").addClass('checkout-step-disable-button');
    $("#step3_button").removeClass('btn-primary');
    $("#address_button").addClass('checkout-step3-button');
    $("#address_button").removeClass('checkout-step-check');
    $("#payment_method_button").addClass('checkout-step-disable-button');
    $("#payment_method_button").removeClass(' checkout-step3-button');
    $("#returnHidden").val(1);
    $(".step2_i_check").hide();
    $("input[name=paymentMethod]:checked").prop( "checked", false );
  }
  function onCheckoutPayment(){
    var product_index = 0;
    var product_list = new Array();
    var quantity_list = new Array();
    $("#product_list_table").find("tr").each(function(){
      product_list[product_index] = $("#product_list_table").find("#product"+product_index).val();
      quantity_list[product_index]= $("#product_list_table").find("#quantity"+product_index).val();
      product_index++;
    });
    $("#doPaypalFrom").append("<input type='hidden' name='product_list[]'  value='"+product_list+"' id='cardFormProductList'>");
    $("#doPaypalFrom").append("<input type='hidden' name='quantity_list[]' value='"+quantity_list+"' id='cardFormQuantityList'>");
    $("#doPaypalFrom").ajaxForm({
      success:function(data){
        if(data.result =="success"){
            $("#paypalForm").find("#custom").val(data.orderID);
            sendPaypal();
        }
      }
    }).submit();
  }
  function sendPaypal(){
    $("#paypalForm").submit();
  }
  function onGoToPayment(){
    $("#step1").removeClass('in');
    $("#step2").removeClass('in');
    $("#step1_button").removeClass('btn-primary');
    $("#step1_button").addClass('checkout-step-check');
    $("#step1_button").removeAttr('data-toggle');
    $("#step2_button").removeClass('btn-primary');
    $("#step2_button").addClass('checkout-step-check');
    $("#step2_button").removeAttr('data-toggle');
    $("#step3_button").add('data-toggle','collapse');
    $("#step3_button").addClass('btn-primary');
    $("#step3_button").removeClass('checkout-step-disable-button');
    $("#step3").addClass('in');
    $(".step1_i_check").css('display','block');
    $(".step2_i_check").css('display','block');
    $("#new_or_returning_button").removeClass('checkout-step3-button');
    $("#new_or_returning_button").addClass('checkout-step-check');
    $("#address_button").removeClass('checkout-step3-button');
    $("#address_button").addClass('checkout-step-check');
    $("#payment_method_button").removeClass('checkout-step-disable-button');
    $("#payment_method_button").addClass(' checkout-step3-button');
  }
  function onChangePaymentCheck(){
    var  method=  $("#step3").find("input[name=paymentMethod]:checked").val();
    if( method == "paypal"){
      $("#completePaymentButtonContent").hide();
      $("#checkoutWithPaypalButtonContent").show();
      $("#cardForm").hide();
      $("#doPaypalForm").show();
    }else{
      $("#completePaymentButtonContent").show();
      $("#checkoutWithPaypalButtonContent").hide();
      $("#cardForm").show();
      $("#doPaypalForm").hide();
    }
  }
  function onCompletePayment(){
  $(".spinner-loader").attr('style','display: block !important');
  $("#completePaymentButtonContent").hide();
    var  method=  $("#step3").find("input[name=paymentMethod]:checked").val();
     if(method == "" || method =="paypal" || method == undefined){
        bootbox.alert("Please select payment method.");
        return;
     }
    var product_index = 0;
    var product_list = new Array();
    var quantity_list = new Array();
    $("#product_list_table").find("tr").each(function(){
      product_list[product_index] = $("#product_list_table").find("#product"+product_index).val();
      quantity_list[product_index]= $("#product_list_table").find("#quantity"+product_index).val();
      product_index++;
    });
    $("#cardForm").append("<input type='hidden' name='product_list[]'  value='"+product_list+"' id='cardFormProductList'>");
    $("#cardForm").append("<input type='hidden' name='quantity_list[]' value='"+quantity_list+"' id='cardFormQuantityList'>");
    $("#cardForm").ajaxForm({
      success:function(data){

        if(data.result == "success"){
          $(".spinner-loader").attr('style','display: none !important');
          $("#completePaymentButtonContent").show();
          window.location.href=data.url;
        }else if(data.result == "paymentFailed"){
          $(".spinner-loader").attr('style','display: none !important');
          $("#completePaymentButtonContent").show();
          bootbox.alert(data.error);
        }else if(data.result == "failed"){
          var arr = data.error;
          var errorList = '';
          $.each(arr, function(index, value)
          {
            if (value.length != 0)
            {
              errorList = errorList + value;
            }
          });
          $("#cardForm").find("#cardFormProductList").remove();
          $("#cardForm").find("#cardFormQuantityList").remove();
          $(".spinner-loader").attr('style','display: none !important');
          $("#completePaymentButtonContent").show();
          bootbox.alert(errorList);
        }
      }
    }).submit();
  }

@endsection
@stop