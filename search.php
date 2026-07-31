<?php
/**
 * The template for displaying search results pages.
 *
 * @package storefront
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header search-page-header">
				<h1 class="page-title">
					<?php
						/* translators: %s: search term */
						printf( esc_html__( 'Search Results for: %s', 'storefront' ), '<span>' . get_search_query() . '</span>' );
					?>
				</h1>
			</header><!-- .page-header -->

			<div class="search-results-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-card' ); ?>>
						<div class="search-card__image">
							<a href="<?php the_permalink(); ?>">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'medium_large' ); ?>
								<?php else : ?>
									<div class="search-card__placeholder">
										<span><?php esc_html_e( 'No Image Available', 'storefront' ); ?></span>
									</div>
								<?php endif; ?>
							</a>
						</div>
						<div class="search-card__content">
							<h2 class="search-card__title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>
							<div class="search-card__excerpt">
								<?php the_excerpt(); ?>
							</div>
							<div class="search-card__action">
								<a href="<?php the_permalink(); ?>" class="search-card__btn pxlt-common-button">
									<?php esc_html_e( 'Read More', 'storefront' ); ?>
								</a>
							</div>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<div class="search-pagination">
				<?php
				the_posts_pagination( array(
					'prev_text'          => __( '&laquo; Previous', 'storefront' ),
					'next_text'          => __( 'Next &raquo;', 'storefront' ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'storefront' ) . ' </span>',
				) );
				?>
			</div>

		<?php
		else :

			get_template_part( 'content', 'none' );

		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();

