@extends('app')

@section('content')
.container
  .order.thanks
    %h1{:style => "text-align:center;"}
      Thank you for your order
    .row.trust
      .col-xs-6.text-center
        %img{:src=>'images/geotrust.jpg'}
      .col-xs-6.text-center
        %img{:src=>'images/quality.png'}
    .row
      .col-md-12
        %h4 Print this for your records. If you have any questions, please contact us at 888-555-1212 or orders@healthiplus.com.
    .row
      .col-md-12
        %h2 Order Detail
        .form-horizontal
          .row.margin-bottom-25
            .col-md-6
              .form-group
                .col-md-4
                  %h4 Transaction #
                .col-md-8
                  %h4  = $order->transaction_id
              .form-group
                .col-md-4
                  %h4 Number
                .col-md-8
                  %h4  = $order->id
            .col-md-6
              .form-group
                .col-md-4
                  %h4 Customer Email
                .col-md-8
                  %h4
                    - if(Auth::user()->id !="")
                      = Auth::user()->email
              .form-group
                .col-md-4
                  %h4 Status
                .col-md-8
                  %h4 = $order->orderStatus($order->status)
          .row.margin-bottom-25
            .col-md-6
              %h2.margin-bottom-25 Billing Address
              -foreach([ 'first_name' => 'First Name', 'last_name' => 'Last Name','address' => 'Address','city' => 'City','state' => 'State','country' => 'Country','postal_code' => 'Postal Code','phone_number' => 'Phone Number'] as $key =>$value)
                .form-group
                  .col-md-4
                    %h4 = $value
                  .col-md-8
                    %h4  = $billingAddress->$key
            .col-md-6
              %h2.margin-bottom-25  Shipping Address
              - if($billingAddress->delivery == 0)
                %h4 Same as billing address
              - else
                -foreach(['first_name' =>'First Name','last_name' => 'Last Name','address' => 'Address','city' => 'City','state' => 'State','country' => 'Country','postal_code' => 'Postal Code'] as $key =>$value)
                  .form-group
                    .col-md-4
                      %h4  = $value
                    .col-md-8
                      %h4  = $delivery->$key







@endsection
@stop