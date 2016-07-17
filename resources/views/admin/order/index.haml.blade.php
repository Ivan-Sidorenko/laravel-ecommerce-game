@extends('admin.layout')
@section('body')
%h3.page-title Orders Management
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
.row
  .col-md-12
    .portlet.box.blue
      .portlet-title
        .caption
          %i.fa.fa-globe
          Orders Management
        .actions
          %form#addfiledForm.display-inline-block.form-horizontal{:action => URL::route('admin.order'), :method => "post"}
            {!! Form::token()!!}
            .form-group.margin-bottom-0
              %label.control-label.col-md-4.col-sm-4
                Sort:
              .col-md-8.col-sm-4
                %select#sortBy.form-control{:name => "sortBy", :onchange => "sortByChange()"}
                  %option{:value => "10", :selected=>($sortByValue ==10)}  All
                  -foreach($list as $key =>$value)
                    %option{:value =>$key, :selected=>($sortByValue ==$key)}
                      = $value
      .portlet-body
        -if(isset($alert))
          .alert.echo.alert-dismissibl.fade.in{:class => "alert-".(isset($alert['type']) ? $alert['type'] : ‘’) }
            %button.close{"data-dismiss" => "alert", :type => "button"}
              %span{"aria-hidden" => "true"} &times;
              %span.sr-only Close
            %p
              = $alert['msg']
        .table-responsive
          %table#sample_1.table.table-striped.table-bordered.table-hover
            %thead
              %tr
                %th Customer
                %th Order No
                %th Items
                %th Total
                %th Created
                %th Status
                %th Action
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
                    = substr($value->created_at,0,10)
                  %td
                    = $value->orderStatus($value->status)
                  %td
                    %a.btn.btn-xs.blue{:href =>  URL::route('admin.order.detail',$value->id)}
                      %i.fa.fa-edit
                      Detail
                    %form#formTest{:action =>  URL::route('admin.order.delete' , $value->id) , :onsubmit => "return onDeleteConfirm(this)", :style => "display:inline-block"}
                      %button#js-a-delete.btn.btn-xs.red{:type => "submit"}
                        %i.fa.fa-trash-o
                        Delete
            .pull-right
              {!! $orders->render() !!}
@stop
@section('custom-scripts')
:javascript
  function sortByChange(){
    $("#addfiledForm").submit();
  }
  function onDeleteConfirm( obj){
    bootbox.confirm("Are you sure?", function(result) {
    if ( result ) {
      obj.submit();
    }
    });

    return false;
  }
@stop
@stop