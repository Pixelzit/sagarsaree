<?php
if ( ! function_exists( 'pxlt_homebanner_shortcode' ) ) {

	function pxlt_homebanner_shortcode() {

		ob_start();

		// $images = array(
		// 	'/wp-content/uploads/2026/06/banner-image.png',
		// 	'/wp-content/uploads/2026/06/banner-image.png',
		// 	'/wp-content/uploads/2026/06/banner-image.png',
		// );
		// ?>

		// <div class="pxlt-homebanner-slider">

		// 	<?php foreach ( $images as $image ) : ?>

		// 		<div class="pxlt-homebanner-slide">
		// 			<img src="<?php echo esc_url( $image ); ?>" alt="Banner Image">
		// 		</div>

		// 	<?php endforeach; ?>

		// </div>

		// <script>
		// jQuery(document).ready(function($){

		// 	$('.pxlt-homebanner-slider').slick({
		// 		dots: true,
		// 		arrows: true,
		// 		autoplay: true,
		// 		autoplaySpeed: 3000,
		// 		infinite: true,
		// 		slidesToShow: 1,
		// 		slidesToScroll: 1
		// 	});

		// });
		// </script>

		<?php

		return ob_get_clean();
	}

	add_shortcode( 'homebanner', 'pxlt_homebanner_shortcode' );
}
?>