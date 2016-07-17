@extends('app')
@section('head')
%meta{:content => "text/html;charset=UTF-8", "http-equiv" => "Content-type"}
@stop
@section('content')
.container.checkout
  .col-md-12
    .table-responsive
      %table.table.table-bordered
        %thead
          %tr
            %th  Order No
            %th  Items
            %th  Total
            %th  Created
            %th  Status
            %th  Actions
        %tbody
          -foreach($orders as $key =>$value)
            -$id="order{$key}"
            %tr
              %td
                -$url = URL::Route('orders.detail',$value->id)
                = $value->id
              %td
                = count($value->items)
              %td
                &pound
                = sprintf('%0.2f',$value->totalOrderDetail())
              %td
                - $date = substr($value->created_at,0,10)
                = date("jS F, Y", strtotime($date))
              %td
                = $value->orderStatus($value->status)
              %td{:style => "width:220px"}
                %a.btn.btn-primary{:href => "javascript:void(0)", :onclick => "onShowDiv(this)"} More Info
                -if($value->status == 0 || $value->status == 1)

                  %form.form-horizontal{:action => URL::route('orders.cancel'), :method => "post", :style => "display:inline-block", :onsubmit => "return onCancelConfirm(this)"}
                    %input{:name => "orderID", :type => "hidden", :value => $value->id}
                    %input{:name => "_token", :type => "hidden", :value => csrf_token()}
                    %input.btn.btn-danger{:type => "submit", :value => "Cancel Order"}
            -$id="order{$key}"
            %tr{:id => $id, :style => "display:none"}
              %td{:colspan => "6"}
                %table.table{:style => "width:100%"}
                  %thead
                    %tr
                      %th
                      %th  Name
                      %th  Quantity
                      %th  Unit Price
                      %th  Total
                  %tbody
                    -foreach($value->items as $key1=>$value1)
                      %tr
                        %td
                          -$url = "/products/{$value1->slug}?vid={$value1->product_variation_id}"
                          %a{:href => $url}
                            %img.img-width-50{:src => $value1->getImageUrlAttribute()}
                        %td
                          -$url = "/products/{$value1->slug}?vid={$value1->product_variation_id}"
                          %a{:href => $url}
                            = $value1->name
                        %td  = $value1->quantity
                        %td
                          &pound
                          = sprintf('%0.2f',$value1->price)
                        %td
                          &pound
                          = sprintf('%0.2f',($value1->quantity * $value1->price))
                  %tfoot
                    %tr
                      %td
                      %td
                      %td
                      %td
                        %b Total
                      %td
                        &pound
                        =  sprintf('%0.2f',$value->totalOrderDetail())
                    %tr
                      %td
                      %td
                      %td
                      %td{:colspan => 2}
                        %a{:href => URL::route('cart.addOrder',$value->id), :class =>"btn btn-primary"} Repeat This Order

%script{:src => "/assets/admin/js/bootbox.js", :type => "text/javascript"}
:javascript
  function onShowDiv(obj){
    var trID =$(obj).closest('tr').next('tr').attr('id');
    $("#"+trID).toggle();
  }

  function onCancelConfirm(obj){
    bootbox.confirm("Are you sure?", function(result) {
     if ( result ) {
       obj.submit();
     }
    });
    return false;
  }
@stop
@stop