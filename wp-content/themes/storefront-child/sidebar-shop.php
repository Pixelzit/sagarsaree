<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package storefront
 */

if ( ! is_active_sidebar( 'filter-sidebar' ) ) {
	return;
}
?>

<div class="filter-slide">
	<div class="custom-filter">
		<button id="show-hidden-filter">show filter</button>
	</div>
	<!-- <div class="custom-apply">
		<?php
			echo do_shortcode('[br_filter_single filter_id=2902]');
		?>
	</div> -->
</div>

<div id="secondary" class="widget-area" role="complementary">
	<?php dynamic_sidebar( 'filter-sidebar' ); ?>
</div>