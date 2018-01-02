<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package ship
 */

if ( ! is_active_sidebar( 'left-sidebar' ) ) {
	return;
}

// when both sidebars turned on reduce col size to 3 from 4.
$sidebar_pos = get_theme_mod( 'understrap_sidebar_position' );
?>

<?php if ( 'both' === $sidebar_pos ) : ?>
<aside class="col-md-3 widget-area" id="left-sidebar" role="complementary">
	<?php else : ?>
<aside class="col-md-4 widget-area" id="left-sidebar" role="complementary">
	<?php endif; ?>
<?php dynamic_sidebar( 'left-sidebar' ); ?>

</aside><!-- #secondary -->