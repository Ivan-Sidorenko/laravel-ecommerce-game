@extends('admin.layout')
@section('body')
.row
  .col-md-12
    .portlet.box.blue
      .portlet-title
        .caption
          %i.fa.fa-globe
          Admin Profile Management
      .portlet-body.form
        -if (isset($alert))
          .alert.echo.alert-dismissibl.fade.in{:class => "alert-".(isset($alert['type']) ? $alert['type'] : ‘’) }
            %button.close{"data-dismiss" => "alert", :type => "button"}
              %span{"aria-hidden" => "true"} &times;
              %span.sr-only Close
            %p
              = $alert['msg']
        %form#addClientForm.form-horizontal{:action =>  URL::route('admin.profilestore') , :enctype => "multipart/form-data", :method => "post", :role => "form"}
          {!! Form::token()!!}
          .form-body
            .form-group
              %label.control-label.col-md-3 Current Password
              .col-md-6
                %input#currentPassword.form-control{:name => "currentPassword", :placeholder => "Current Password", :type => "password"}
            .form-group
              %label.control-label.col-md-3 New Password
              .col-md-6
                %input#newPassword.form-control{:name => "newPassword", :placeholder => "New Password", :type => "password"}
            .form-group
              %label.control-label.col-md-3 Re-type New Password
              .col-md-6
                %input#confirmNewPassword.form-control{:name => "confirmNewPassword", :placeholder => "Confirm New Password", :type => "password"}
          .form-actions
            .row
              .col-md-offset-7.col-md-5
                %button.btn.blue{:type => "submit"}
                  %i.fa.fa-check-circle-o{:style => "margin-right:4px"}>
                  Save
                %a.btn.green{:href => URL::route('admin.dashboard')}
                  %i.fa.fa-repeat{:style => "margin-right:4px"}>
                  Cancel
@stop
@stop