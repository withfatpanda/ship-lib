<!doctype html>
<html @php(language_attributes())>
  @include('partials.head')
  <body @php(body_class())>
    @php(do_action('get_header'))
    @include('partials.header')
    <div class="wrapper" id="wrapper-index" role="document">
      <div class="{{ container_type() }}" id="content">
        <div class="row">
          @include('partials.left-sidebar-check-none')
            <main class="site-main" id="main">
              @yield('content')
            </main><!--/main-->
          </div>
          @if(display_sidebar('right'))
            @include('partials.sidebar-right')
          @endif
        </div>
      </div>
    </div>
    @php(do_action('get_footer'))
    @include('partials.footer')
    @php(wp_footer())
  </body>
</html>
