<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package ship
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside class="col-md-4 widget-area" id="secondary" role="complementary">

	<?php dynamic_sidebar( 'sidebar-1' ); ?>

</aside><!-- #secondary -->
