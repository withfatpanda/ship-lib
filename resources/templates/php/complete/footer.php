<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package ship
 */

$the_theme = wp_get_theme();
$container = get_theme_mod( 'understrap_container_type' );
?>

<?php get_sidebar( 'footerfull' ); ?>

<div class="wrapper" id="wrapper-footer">

	<div class="<?php echo esc_attr( $container ); ?>">

		<div class="row">

			<div class="col-md-12">

				<footer class="site-footer" id="colophon">

					<!--

					<div class="site-info">

							<a href="<?php  echo esc_url( __( 'http://wordpress.org/', 'understrap' ) ); ?>"><?php printf( 
							/* translators:*/
							esc_html__( 'Proudly powered by %s', 'understrap' ), 'WordPress' ); ?></a>
								<span class="sep"> | </span>
					
							<?php printf( // WPCS: XSS ok.
							/* translators:*/
								esc_html__( 'Theme: %1$s by %2$s.', 'understrap' ), $the_theme->get( 'Name' ),  '<a href="'.esc_url( __('https://www.withfatpanda.com', 'understrap')).'">Fat Panda</a>' ); ?> 
				
							(<?php printf( // WPCS: XSS ok.
							/* translators:*/
								esc_html__( 'Version: %1$s', 'understrap' ), $the_theme->get( 'Version' ) ); ?>)
					</div>

					-->

				</footer><!-- #colophon -->

			</div><!--col end -->

		</div><!-- row end -->

	</div><!-- container end -->

</div><!-- wrapper end -->

</div><!-- #page we need this extra closing tag here -->

<?php wp_footer(); ?>

</body>

</html>

