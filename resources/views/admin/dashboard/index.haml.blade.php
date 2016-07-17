@extends('admin.layout')
@section('body')
.row
  .col-md-12
    .portlet.light
      .portlet-body
        .alert.alert-success
          %span.caption-subject.bold.fontSize25
          Welcome
          %br
          %strong Success!
          You can use admin panel for your action.
.row
  .col-md-6
    .portlet.light
      .portlet-title
        .caption
          %i.fa.fa-cogs.font-green-sharp
          %span.caption-subject.font-green-sharp.bold.uppercase Orders
      .portlet-body
        .table-responsive
          %table#sample_1.table.table-striped.table-bordered.table-hover
            %thead
              %tr
                %th Customer
                %th Order No
                %th Items
                %th Total
                %th Status
            %tbody
              - foreach($orders as $key =>$value)
                %tr
                  %td
                    =  $value->user->name
                  %td
                    %a{:href =>  URL::Route('admin.order.detail',$value->id) , :target => "_blank"} =  $value->id
                  %td
                    = count($value->items)
                  %td
                    &pound
                    = $value->totalOrderDetail()
                  %td
                    = $value->orderStatus($value->status)
            .pull-right
              {!! $orders->render() !!}

@stop
@stop