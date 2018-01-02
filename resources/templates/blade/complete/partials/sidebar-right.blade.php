@if (is_active_sidebar('right-sidebar'))
  
  @if('both' === display_sidebar())
    <aside class="col-md-3 widget-area" id="right-sidebar" role="complementary">
  @else
    <aside class="col-md-4 widget-area" id="right-sidebar" role="complementary">
  @endif

  @php(dynamic_sidebar('right-sidebar'))

  </aside><!-- /#right-sidebar -->

@endif