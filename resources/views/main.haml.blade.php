!!!
/[if IE 8] <html lang="en" class="ie8 no-js">
/[if IE 9] <html lang="en" class="ie9 no-js">
%html{:lang => "en"}
  %head
    %meta{:charset => "utf-8"}/
    %meta{:content => "IE=edge", "http-equiv" => "X-UA-Compatible"}
    %meta{:content => "width=device-width, initial-scale=1.0", :name => "viewport"}/
    %meta{:content => "text/html; charset=utf-8", "http-equiv" => "Content-type"}
    %meta{:content => "", :name => "description"}/
    %meta{:content => "", :name => "author"}/
    %link{:href => "{{ public_path()}}/favicon.ico", :rel => "shortcut icon"}
    %title
      @yield('title')
    @yield('styles')
    @yield('custom-styles')
  @yield('content')
  @yield('scripts')
  @yield('custom-scripts')