@extends('admin.layout')
@section('body')
%h3.page-title Order Detail Management
.page-bar
  %ul.page-breadcrumb
    %li
      %i.fa.fa-home
      %a{:href => URL::route('admin.dashboard')} Home
      %i.fa.fa-angle-right
    %li
      %i.fa.fa-pencil
      %a{:href => URL::route('admin.order')} Orders
      %i.fa.fa-angle-right
    %li
      %i.fa.fa-pencil
      %a{:href => URL::route('admin.order.detail', $order->id)} Order Detail
.row
  .col-md-12
    .portlet.light
      .portlet-body
        .row
          .col-md-6
            %h3.margin-bottom-20
              Order Number:
              =  $order->id
            %h4
              Customer
              = $order->user->name
            - if($order->status == 5)
              %h4.color-red
                Status:
                = $order->orderStatus($order->status)
              %h4.color-red
                Previous Status:
                = $order->orderStatus($order->previous_status)
            - else
              %h4
                Status:
                = $order->orderStatus($order->status)
            %h4
              Transaction ID:
              = $order->transaction_id
            %h4
              Shipping Detail:
              = $order->shippingDetail
            %h4
              Created:
              = substr($order->created_at,0,10)
            %h4
              Updated:
              = substr($order->updated_at,0,10)
          .col-md-6
            - if (isset($alert) && $alert['list'] == "refundAgreeSuccessfully")
              .alert.echo.alert-dismissibl.fade.in{:class => "alert-".(isset($alert['type']) ? $alert['type'] : ‘’) }
                %button.close{"data-dismiss" => "alert", :type => "button"}
                  %span{"aria-hidden" => "true"} &times;
                  %span.sr-only Close
                %p
                  = $alert['msg']
            - if($order->status == 5)
              .col-md-12.margin-bottom-20
                %h3.color-red.margin-bottom-30
                  Do you agree cancel this order?
                %form.form-horizontal{:action => URL::route('admin.order.cancelAgree'), :method => "post", :onsubmit => "return onCancelConfirm(this)"}
                  {!! Form::token()!!}
                  %input{:name => "orderID", :type => "hidden", :value => $order->id}
                  %input{:name => "refund_agree", :type => "checkbox", :value => "1"} Do you want refund also ?
                  %input.btn.red.float-right{:type => "submit", :value => "Agree"}
            .col-md-12
              %h3 Status Update
              - if(isset($alert) && $alert['list'] == "statusUpdateSuccessfully")
                .alert.echo.alert-dismissibl.fade.in{:class => "alert-".(isset($alert['type']) ? $alert['type'] : ‘’) }
                  %button.close{"data-dismiss" => "alert", :type => "button"}
                    %span{"aria-hidden" => "true"} &times;
                    %span.sr-only Close
                  %p
                    = $alert['msg']
              %form.form-horizontal{:action => URL::route('admin.order.detailUpdate'), :method => "post"}
                {!! Form::token()!!}
                %input{:name => "orderID", :type => "hidden", :value => $order->id}
                .form-group
                  %label.control-label.col-md-4.col-sm-4
                    Order Status
                  .col-md-8.col-sm-8
                    %select#status.form-control{:name => "status"}
                      -foreach($list as $key =>$value)
                        %option{:value =>$key, :selected=>($order->status ==$key)}
                          = $value

                .form-group
                  .col-md-8.col-sm-8.col-md-offset-4.col-sm-offset4
                    %input.btn.blue.float-right{:type => "submit", :value => "Update"}
            .col-md-12
              %h3
                Order Shipping Detail Update
              - if (isset($alert) && $alert['list'] == "shippingDetailUpdateSuccessfully")
                .alert.echo.alert-dismissibl.fade.in{:class => "alert-".(isset($alert['type']) ? $alert['type'] : ‘’) }
                  %button.close{"data-dismiss" => "alert", :type => "button"}
                    %span{"aria-hidden" => "true"} &times;
                    %span.sr-only Close
                  %p
                    = $alert['msg']
              - if ($errors->has())
                .alert.alert-danger.alert-dismissibl.fade.in
                  %button.close{"data-dismiss" => "alert", :type => "button"}
                    %span{"aria-hidden" => "true"} &times;
                    %span.sr-only Close
                  - foreach ($errors->all() as $error)
                    = $error
              %form.form-horizontal{:action => URL::route('admin.order.shippingDetailUpdate'), :method => "post"}
                {!! Form::token()!!}
                %input{:name => "orderID", :type => "hidden", :value => $order->id}
                .form-group
                  %label.control-label.col-md-4.col-sm-4
                    Shipping Detail
                  .col-md-8.col-sm-8
                    %input.form-control{:name => "shippingDetail", :placeholder => "ShippingDetail", :type => "text", :value => $order->shippingDetail}
                .form-group
                  .col-md-8.col-sm-8.col-md-offset-4.col-sm-offset4
                    %input.btn.blue.float-right{:type => "submit", :value => "Update"}
        .row
          .col-md-12
            %h4 Items
            .table-responsive
              %table.table.table-bordered
                %thead
                  %tr
                    %th Name
                    %th Quantity
                    %th Unit Price
                    %th Total
                %tbody
                  - foreach($order->items as $key=>$value)
                    %tr
                      %td
                        = $value->name
                      %td
                        = $value->quantity
                      %td
                        &pound
                        = $value->price
                      %td
                        &pound
                        = $value->quantity * $value->price
                %tfoot
                  %tr
                    %td
                    %td
                    %td
                      %b Total
                    %td
                      &pound
                      = $order->totalOrderDetail()
@stop
@section('custom-scripts')
:javascript
  function onCancelConfirm( obj){
    bootbox.confirm("Are you sure?", function(result) {
      if ( result ) {
        obj.submit();
      }
    });
    return false;
  }
@stop