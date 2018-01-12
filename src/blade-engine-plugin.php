<?php
use duncan3dc\Laravel\Blade;
use FatPanda\WordPress\Ship\BladeInstance;

/**
 * Either build and return a BladeInstance, or, if given a template
 * name to render, return the HTML built from the template name.
 * @param  string $template Optionally, a template to render
 * @param  array  $data Optionally, data to use in rendering
 * @return mixed Either 
 */
function blade($template = '', $data = []) 
{
  static $blade;

  if (empty($blade)) {

    $blade = new BladeInstance(get_template_directory(), wp_upload_dir()['basedir'].'/.blade-cache');

    // Blade::setInstance($blade);

    do_action('blade_init', $blade);

  }

  if (!empty($template)) {

    return $blade->render($template, $data);

  } else {

    return $blade;

  }
}

function echo_option_or_default($optionName, $default = null)
{
  echo (string) get_option($optionName) ?: $default;
}

add_action('blade_init', function($blade) {

  // create directive aliases for common WP template tags
  $alias = function($name) use ($blade) {
    // allow for two expressions of $name: 
    // 1. $name is both the directive and the function to alias
    // 2. $name is a hash: [ $directiveName => $functionName ]
    $name = !is_array($name) ? [ $name => $name ] : $name;
    $directiveName = array_keys($name)[0];
    $functionName = array_values($name)[0];
    $blade->directive($directiveName, function($expression = null) use ($functionName) {
      return "<?php {$functionName}({$expression}); ?>";
    });
  };

  collect([
    'do_action', 
    'language_attributes', 
    'body_class', 
    'wp_head',
    'wp_footer',
    'wp_nav_menu',
    'add_filter',
    'apply_filters',
    'the_content',
    'get_option',
    'the_post',
    'wp_reset_postdata',
    [ 'filter' => 'add_filter' ]
  ])->each(function($name) use ($alias) {
    $alias($name);  
  });

  $blade->directive('addBodyClass', function($expression = null) {
    return "<?php add_filter('body_class', function(\$classes) { return array_merge(\$classes, (array) {$expression}); }); ?>";
  });

  $blade->directive('option', function($expression) {
    return "<?php echo_option_or_default($expression); ?>";
  });

  $blade->directive('setup_postdata', function($expression) {
    return "<?php global \$post; \$post = {$expression}; setup_postdata(\$post); ?>";
  });

});

/**
 * This bit was taken straight out of Sage, but their code is in two places for some reason
 * Basically what this does is it supplements the WordPress template hiearchy
 * with a bunch of .blade.php alternatives such that if a .blade.php file is found, it
 * supercedes the normal .php file in importance.
 * @see https://github.com/roots/sage/blob/master/app/setup.php#L102
 * @see https://github.com/roots/sage/blob/master/app/filters.php
 */
collect([
  'index', 
  '404', 
  'archive', 
  'author', 
  'category', 
  'tag', 
  'taxonomy', 
  'date', 
  'home',
  'frontpage', 
  'page', 
  'paged', 
  'search', 
  'single', 
  'singular', 
  'attachment',
])->map(function ($type) {

  add_filter("{$type}_template_hierarchy", function($templates) {
    
    $paths = [ get_template_directory() ];

    $paths_pattern = "#^(" . implode('|', $paths) . ")/#";

    return collect($templates)->map(function ($template) use ($paths_pattern) {

      /** Remove .blade.php/.blade/.php from template names */
      $template = preg_replace('#\.(blade\.?)?(php)?$#', '', ltrim($template));

      /** Remove partial $paths from the beginning of template names */
      if (strpos($template, '/')) {
        $template = preg_replace($paths_pattern, '', $template);
      }

      return $template;

    })->flatMap(function ($template) use ($paths) {

      return collect($paths)->flatMap(function($path) use ($template) {
        return [
            "{$path}/{$template}.blade.php",
            "{$path}/{$template}.php",
            "{$template}.blade.php",
            "{$template}.php",
        ];
      });

    })->filter()
      ->unique()
      ->all();

  });

});

add_filter('template_include', function($phpPath) {
  global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;
  
  if (preg_match('#/([^/]+?)((.blade)?.php)$#i', $phpPath, $matches)) {
  
    $templateName = $matches[1];
    $bladePath = $phpPath;
    
    if (empty($matches[3])) {
      $bladePath = preg_replace('#(.php)$#i', '.blade.php', $phpPath);
    }

    if (file_exists($bladePath)) {
      $data = [
        'posts' => $posts,
        'post' => $post,
        'wp_did_header' => $wp_did_header,
        'wp_query' => $wp_query,
        'wp_rewrite' => $wp_rewrite,
        'wpdb' => $wpdb,
        'wp_version' => $wp_version,
        'wp' => $wp,
        'id' => $id,
        'comment' => $comment,
        'user_ID' => $user_ID,
      ];

      if ( is_array( $wp_query->query_vars ) ) {
        $data = array_merge( $wp_query->query_vars, $data );
      }

      // automatically escape incoming search 
      if (!empty($data['s'])) {
        $data['s'] = esc_attr($data['s']);
      }

      echo blade($templateName, $data);
      // always tell WordPress to load this empty template instead
      return __DIR__ . '/empty.php';
    }

  } 
  
  return $phpPath;
}, PHP_INT_MAX, 10);

/**
 * Retrieve path to a compiled blade view
 * @param $file
 * @param array $data
 * @return string
 */
function template_path($file, $data = [])
{
  return blade()->compiledPath($file, $data);
}

/**
 * Tell WordPress how to find the compiled path of comments.blade.php
 */
add_filter('comments_template', function ($comments_template) {
  $comments_template = str_replace(
    [get_stylesheet_directory(), get_template_directory()],
    '',
    $comments_template
  );
  return template_path(locate_template(["views/{$comments_template}", $comments_template]) ?: $comments_template);
});

/**
 * Add <body> classes
 */
add_filter('body_class', function (array $classes) {
  /** Add page slug if it doesn't exist */
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
      $classes[] = basename(get_permalink());
    }
  }

  /** Add class if sidebar is active */
  if (display_sidebar()) {
    $classes[] = 'sidebar-primary';
  }

  /** Clean up class names for custom templates */
  $classes = array_map(function ($class) {
    return preg_replace(['/-blade(-php)?$/', '/^page-template-views/'], '', $class);
  }, $classes);

  return array_filter($classes);
});

/**
 * If the searchform blade partial is found, use it to render
 * the search form instead of the default.
 */
add_filter('get_search_form', function ($html) {
  if (locate_template('partials/searchform.blade.php')) {
    return blade()->render('partials.searchform', []);
  }
  return $html;
});