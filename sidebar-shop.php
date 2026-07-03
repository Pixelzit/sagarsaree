<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package storefront
 */

$sidebar_id = 'filter-sidebar';
if ( ! is_active_sidebar( $sidebar_id ) ) {
	$sidebar_id = 'sidebar-1';
}

if ( ! is_active_sidebar( $sidebar_id ) ) {
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
			// echo do_shortcode('[br_filters_group group_id=3136]');
		?>
	</div> -->
</div>

<div id="secondary" class="widget-area" role="complementary">
	<?php dynamic_sidebar( $sidebar_id ); ?>
</div>