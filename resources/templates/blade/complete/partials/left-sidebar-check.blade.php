@if(display_sidebar('left'))
  @include('partials.sidebar-left')
@endif

@if (display_sidebar())
  @if (is_active_sidebar('right-sidebar') || is_active_sidebar('left-sidebar'))
    <div class="col-md-8 content-area" id="primary">
  @else
    <div class="col-md-12 content-area" id="primary">
  @endif  
@elseif (is_active_sidebar('right-sidebar') && is_active_sidebar('left-sidebar'))
  @if (display_sidebar('both'))
    <div class="col-md-6 content-area" id="primary">
  @else
    <div class="col-md-12 content-area" id="primary">
  @endif
@else
  <div class="col-md-12 content-area" id="primary">
@endif