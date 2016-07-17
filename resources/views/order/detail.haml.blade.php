@extends('app')
@section('content')
.container.checkout
  .col-md-10.col-md-offset-1
    .row
      .col-md-6
        %h3.margin-bottom-20
          Order Number:
          = $order->id
        %h4
          Customer:
          = $order->user->name
        %h4
          Status:
          = $order->orderStatus($order->status)
        %h4
          Shipping Detail:
          = $order->shippingDetail
        %h4
          Created:
          - $date = substr($order->created_at,0,10)
          = date("jS F, Y", strtotime($date))
    .row
      .col-md-12
        %h4
          Items
          .table-responsive
            %table.table.table-bordered
              %thead
                %tr
                  %th  Name
                  %th  Quantity
                  %th  Unit Price
                  %th  Total
              %tbody
                -foreach($order->items as $key=>$value)
                  %tr
                    %td  = $value->name
                    %td  = $value->quantity
                    %td  = $value->price
                    %td  = $value->quantity * $value->price
              %tfoot
                %tr
                  %td
                  %td
                  %td
                    %b Total
                  %td
                    = $order->totalOrderDetail()
@stop
@stop