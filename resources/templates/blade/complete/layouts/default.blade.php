<!doctype html>
<html @php(language_attributes())>
  @include('partials.head')
  <body @php(body_class())>
    @php(do_action('get_header'))
    @include('partials.header')
    @section('wrapper')
      <div class="wrapper" id="wrapper-index" role="document">
        <div class="{{ container_type() }}" id="content">
          <div class="row">
            @include('partials.left-sidebar-check-none')
              @section('main')
                <main class="site-main" id="main">
                  @yield('content')
                </main><!--/main-->
              @show
            </div>
            @section('sidebar-right')
              @if(display_sidebar('right'))
                @include('partials.sidebar-right')
              @endif
            @show
          </div>
        </div>
      </div>
    @show
    @php(do_action('get_footer'))
    @include('partials.footer')
    @php(wp_footer())
  </body>
</html>
