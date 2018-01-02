<div class="wrapper-fluid wrapper-navbar" id="wrapper-navbar">
  <a class="skip-link screen-reader-text sr-only" href="#content">{{ esc_html_e('Skip to content', 'understrap') }}</a>

    <nav class="navbar navbar-expand-md navbar-dark bg-dark">

      @if(container_type('container'))
        <div class="container">
      @endif

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      @if (!has_custom_logo())
        @if (is_front_page() && is_home())
          <h1 class="navbar-brand mb-0">
            <a rel="home" href="{{ home_url('/') }}" title="{{ get_bloginfo('name', 'display') }}">
              {{ get_bloginfo('name') }}
            </a>
          </h1>
        @else
          <a class="navbar-brand" rel="home" href="{{ home_url('/') }}" title="{{ get_bloginfo('name', 'display') }}">
            {{ get_bloginfo('name') }}
          </a>
        @endif
      @else
        @php the_custom_logo() @endphp
      @endif

      @php
        wp_nav_menu([
          'theme_location'  => 'primary',
          'container_class' => 'collapse navbar-collapse',
          'container_id'    => 'navbarNavDropdown',
          'menu_class'      => 'navbar-nav',
          'fallback_cb'     => '',
          'menu_id'         => 'main-menu',
          'walker'          => new WP_Bootstrap_Navwalker,
        ])
      @endphp
      
      @if (container_type('container'))
        </div><!-- /.container -->
      @endif

    </nav><!-- .site-navigation -->

  </div><!-- .wrapper-navbar end -->