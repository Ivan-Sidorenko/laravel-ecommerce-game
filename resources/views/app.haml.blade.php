!!!
%html{:lang => "en"}
  %head
    %meta{:charset => "utf-8"}
      %meta{:name => "viewport", :content => "user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=320, height=device-height, target-densitydpi=medium-dpi"}
        %title @yield('title')
        %link#pif-styles-css{:href => elixir('assets/css/all.css'), :media => "all", :rel => "stylesheet", :type => "text/css"}/
        %script{:src => elixir('assets/js/app.js')}
        @yield('head')
        %meta{:content => "DD4n4Dtdypa9Q-LzrflWZhqdscL0G8Psbf2nD2_noCM", :name => "google-site-verification"}/
  %body
    %header
      .row.first
        .col-xs-6.col-md-4
          %a{:href => "/"}
            %img.logo{:alt => "", :src => "/images/Logo-white-on-trans.png"}/
        .col-md-4.visible-md-block.visible-lg-block
          %form{:method=>'get', :action=>route('search')}
            .input-group
              %input.form-control{:placeholder => "SEARCH", :type => "text", :name=>'s'}
                %span.input-group-btn
                  %button.btn.btn-default{:style => "background-color: #1DAEEB", :type => "submit"}
                    %i.fa.fa-search
          / /input-group
        .col-xs-6.icons.col-md-4.pull-right
          .dropdown
            %a.icon.dropdown-toggle{:href => "#"}
              %i.fa.fa-user
              %br/
              Account
            -if(!Auth::check())
              .dropdown-menu.account{:style => "padding: 15px; padding-bottom: 0px;"}
                %ul.nav.nav-tabs{"role" => "tablist"}
                  %li.active{"role" => "presentation"}
                    %a{:href => "#login", "aria-controls" => "home", "role" => "tab", "data-toggle" => "tab"}
                      Log In
                  %li{"role" => "presentation"}
                    %a{:href => "#sign-up", "aria-controls" => "sign-up", "role" => "tab", "data-toggle" => "tab"}
                      Sign up
                .tab-content
                  .tab-pane.fade.in.active.social-icons{"role" => "tabpanel", :id => "login"}
                    %br/
                    %span
                      Log in with:
                    %a.fa.fa-facebook{:href => route("sso_auth",["facebook"])}
                    %a.fa.fa-twitter{:href => route("sso_auth",["twitter"])}
                    %a.fa.fa-google-plus{:href => route("sso_auth",["google"])}
                    %br/
                    %br/
                    .hr{:style => "height: 1px; background-color: #ccc; text-align: center"}
                      %span{:style => "background-color: white; position: relative; top: -0.7em;"}
                        &nbsp;&nbsp;&nbsp;&nbsp;or&nbsp;&nbsp;&nbsp;&nbsp;
                    %br/
                    %form{:method => "post", :action => route('login_post'), "accept-charset" => "UTF-8"}
                      -echo csrf_field()
                      .row
                        .col-xs-10.col-sm-10.col-md-10.col-lg-10.col-xs-offset-1.col-sm-offset-1.col-md-offset-1.col-lg-offset-1
                          .form-group
                            %input{:type => "email", :class => "form-control", :placeholder => "Email", :name => "email", :value => old('email')}/
                          .form-group
                            %input{:class => "form-control", :type => "password", :placeholder => "Password", :id => "password", :name => "password"}/
                          .checkbox.pull-left
                            %label
                              %input{:type => "checkbox", :name => "remember"}/
                              Remember Me
                          .pull-right
                            %button.btn.btn-primary{:type => "submit"}
                              Log In
                    %br/
                    .panel-footer
                      %span
                        New to Only Muscle?
                      %a{:href => "#"}
                        Sign Up Now
                  .tab-pane.fade.social-icons{"role" => "tabpanel", :id => "sign-up"}
                    %br/
                    %span
                      Sign up with:
                    %a.fa.fa-facebook{:href => route("sso_auth",["facebook"])}
                    %a.fa.fa-twitter{:href => route("sso_auth",["twitter"])}
                    %a.fa.fa-google-plus{:href => route("sso_auth",["google"])}
                    %br/
                    %br/
                    .hr{:style => "height: 1px; background-color: #ccc; text-align: center"}
                      %span{:style => "background-color: white; position: relative; top: -0.7em;"}
                        &nbsp;&nbsp;&nbsp;&nbsp;or&nbsp;&nbsp;&nbsp;&nbsp;
                    %br/
                    %form{:method => "post", :action => route('register_post'), "accept-charset" => "UTF-8"}
                      -echo csrf_field()
                      .row
                        .col-xs-10.col-sm-10.col-md-10.col-lg-10.col-xs-offset-1.col-sm-offset-1.col-md-offset-1.col-lg-offset-1
                          .form-group
                            %input{:class => "form-control", :placeholder => "Username", :name => "name", :value => old('name')}/
                          .form-group
                            %input{:class => "form-control", :type => "password", :placeholder => "Password", :id => "password", :name => "password"}/
                          .form-group
                            %input{:class => "form-control", :type => "password", :placeholder => "Repeat Password", :id => "password_confirmation", :name => "password_confirmation"}/
                          .form-group
                            %input{:class => "form-control", :type => "email", :placeholder => "Email", :name => "email", :value => old('email')}/
                          .checkbox
                            %label
                              %input{:type => "checkbox", :name => "terms_and_conditions", :value => "1"}
                                Please click to confirm that you have read and agree to our
                                %a{:href => "#"}
                                  terms and conditions
                          %button.btn.btn-primary{:type => "submit"}
                            Complete Sign Up
                          %br/
                          %br/
            -if(Auth::check())
              .dropdown-menu.account{:style => "padding: 15px; padding-bottom: 0px;"}
                .raw
                  .col-xs-8.col-sm-8.col-md-8.col-lg-8
                    -echo Auth::user()->name
                    %br/
                    %a{:href => "#"}
                      Profile
                    %br/
                    %a{:href => "#"}
                      Orders
                    %br/
                    %a{:href => route('logout')}
                      Log Out
                    %br/
                    %br/
                  .col-xs-4.col-sm-4.col-md-4.col-lg-4
                    -if(!empty(Auth::user()->social_avatar))
                      %img{:alt => Auth::user()->name, :src => Auth::user()->social_avatar, :class=>"img-responsive"}
                    %br/
          .icon
            %a{:href => "#"}
              %i.fa.fa-shopping-cart
              %br/
              Basket
          .icon
            %a.icon{:href => route('cart.view')}
              %i.fa.fa-comment
              %br/
              Contact
      .row.second
        .col-xs-12
          %ul.nav-btn.pull-left.list-unstyled
            %li{:class => Route::currentRouteName()=='store' ? 'active' : ''}
              %a{:href => route('store')} Store
            %li
              %a{:href => "#"} Training
            %li
              %a{:href => "#"} Diet
          %ul.nav-btn.nav-small.pull-right.list-unstyled
            %li
              %a{:href => "#"} Gyms
            %li
              %a{:href => "#"} Sports
      .row.spacer.visible-md-block.visible-lg-block
      .row.third-large.visible-md-block.visible-lg-block
        .col-xs-4.text-center
          %i.fa.fa-trophy
          %b #1 supplement rating system
          in the world
        .col-xs-4.text-center
          %i.fa.fa-truck
          %b Free UK Delivery
          on all orders
        .col-xs-4.text-center
          %i.fa.fa-clock-o
          Order within
          %b 2h 2m 16s
          \&amp; receive tomorrow.
      .row.third.hidden-md.hidden-lg
        .col-xs-12
          .row
            .col-xs-6
              %form{:method=>'get', :action=>route('search')}
                .input-group
                  %input.form-control{:placeholder => "SEARCH", :type => "text", :name=>'s'}
                    %span.input-group-btn
                      %button.btn.btn-default{:style => "background-color: #1DAEEB", :type => "submit"}
                        %i.fa.fa-search
              / /input-group
            .col-xs-6
              .delivery
                .fa.fa-truck
                .text
                  Free UK Delivery
                  .small On all orders
      .row.fourth.hidden-md.hidden-lg
        .col-xs-4
          %select.categories.form-control
            %option Categories
            -$cats = App\Tier::category()->get()
            -foreach($cats as $cat)
              %option{"data-id"=>$cat->id, "data-url"=>route('category.view', ['slug'=>$cat->slug])} =$cat->name
          :javascript
            $(function() {
              $('select.categories').change(function() {
                $( "select.categories option:selected" ).each(function() {
                  document.location = $(this).data('url');
                }); 
              });
            });
        .col-xs-4
          %select.form-control
            %option Brands
        .col-xs-4
          %select.form-control
            %option Goals
    -foreach(['danger', 'warning', 'success'] as $type)
      -if(session()->has($type))
        -$rows = session()->get($type)
        -$rows = is_array($rows) ? $rows : [$rows]
        -if(count($rows)>0)
          .alert{:class=>'alert-'.$type}
            -foreach($rows as $notice)
              =$notice
              %br
    -if(count($errors) > 0)
      .alert.alert-danger
        %ul
          -foreach ($errors->all() as $error)
            %li
              =$error
    %div{:class => slugify(Route::currentRouteName())}
      .container-fluid
        @yield('content')
    %div{:style => "clear:both;"}
    %footer
      .container
        .row
          .col-xs-12
            %h1 The Home of UK Muscle and Fitness
            %h2 Follow Only Muscle
            .social-icons
              %a.fa.fa-facebook{:href => "#"}
              %a.fa.fa-twitter{:href => "#"}
              %a.fa.fa-google-plus{:href => "#"}
              %a.fa.fa-youtube{:href => "#"}
              %a.fa.fa-instagram{:href => "#"}
            %h2 Contact Us
            .contact.small
              .row
                .col-xs-6
                  (address)
                .col-xs-6
                  (phone)
            .copyright.small
              \&copy;Copyright {{{date('Y')}}}, AMAS Online Limited. All content of this site is owned by AMAS Online Limited
    @yield('footer')