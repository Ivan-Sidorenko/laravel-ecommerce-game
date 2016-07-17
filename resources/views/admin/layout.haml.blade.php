@extends('main')
@section('title')
ADMIN|ONLY MUSCLE
@stop
@section('styles')
%link{:href => "//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/font-awesome.min.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/simple-line-icons.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/simple-line-icons.min.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/bootstrap.min.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/uniform.default.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/bootstrap-switch.min.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/bootstrap-wysihtml5.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/jquery.fancybox.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/jquery.fileupload.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/jquery.fileupload-ui.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/blueimp-gallery.min.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/inbox.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/daterangepicker-bs3.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/fullcalendar.min.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/jqvmap.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/tasks.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/select2.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/components.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/plugins.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/layout.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/default.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/custom.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/dataTables.bootstrap.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
%link{:href => "/assets/admin/css/ivan.css", :media => "all", :rel => "stylesheet", :type => "text/css"}
@stop
@section('content')
%body.page-boxed.page-header-fixed.page-sidebar-closed-hide-logo.page-container-bg-solid.page-sidebar-closed-hide-logo
  .page-header.navbar.navbar-fixed-top
    .page-header-inner
      .page-logo
        %a{:href => URL::route('admin.dashboard')}
          %img.logo-default{:alt => "logo", :src => "/assets/admin/img/logo.png"}
        .menu-toggler.sidebar-toggler
      %a.menu-toggler.responsive-toggler{"data-target" => ".navbar-collapse", "data-toggle" => "collapse", :href => "javascript:;"}
      .page-top
        .top-menu
          %ul.nav.navbar-nav.pull-right
            %li.dropdown.dropdown-user
              %a.dropdown-toggle{"data-close-others" => "true", "data-hover" => "dropdown", "data-toggle" => "dropdown", :href => "#"}
                %span.username.username-hide-on-mobile.loginTopColor
                Account
                %i.fa.fa-angle-down.loginTopColor
              %ul.dropdown-menu.dropdown-menu-default.loginTopColor
                %li
                  %a.loginTopColor{:href => URL::route('admin.profile')}
                    %i.icon-user
                    My Profile
                %li
                  %a.loginTopColor{:href => URL::route('admin.auth.logout')}
                    %i.icon-key
                    Log Out
  .clearfix
  .page-container
    .page-sidebar-wrapper
      .page-sidebar.navbar-collapse.collapse
        %ul.page-sidebar-menu.page-sidebar-menu-hover-submenu{"data-auto-scroll" => "true", "data-keep-expanded" => "false", "data-slide-speed" => "200"}
          - $active = "active"
          %li{:class => "start" . ($pageNo==1 ? " active" : "")}
            %a{:href =>  URL::route('admin.dashboard')}
              %i.fa.fa-tachometer
              %span.title Dashboard
              %span.selected
          %li{:class => "start" . ($pageNo==2 ? " active" : "")}
            %a{:href => URL::route('admin.order')}
              %i.fa.fa-reorder
              %span.title Orders
              %span.selected
          %li
            \&nbsp;
      .page-content-wrapper.min-height-1000
        .page-content.min-height-1000
          @yield('body')
@stop
@section ('scripts')
%script{:src => "http://www.newrepository.localhost/assets/admin/js/jquery.min.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/jquery-migrate.min.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/jquery-ui-1.10.3.custom.min.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/bootstrap-hover-dropdown.min.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/bootstrap.min.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/jquery.slimscroll.min.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/jquery.blockui.min.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/jquery.uniform.min.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/bootstrap-switch.min.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/jquery.pulsate.min.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/moment.min.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/daterangepicker.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/jquery.easypiechart.min.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/jquery.sparkline.min.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/jquery.validate.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/jquery.backstretch.min.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/select2.min.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/metronic.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/layout.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/layout2/layout.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/layout2/demo.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/index.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/tasks.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/jquery.dataTables.min.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/dataTables.bootstrap.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/professions.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/bootbox.js"}
%script{:src => "http://www.newrepository.localhost/assets/admin/js/jquery.form.js"}
:javascript
  jQuery(document).ready(function() {
    Metronic.init(); // init metronic core componets
    Layout.init(); // init layout
    Demo.init(); // init demo features
  });
@stop
@stop