<?php
/**
 * The template for displaying all single posts.
 *
 * @package Theme Freesia
 * @subpackage Magbook
 * @since Magbook 1.0
 */
$args = array(
        'include'  => get_the_ID(),
        'category_name' => 'opinion' ,
        'fields'   => 'ids',
    );
    
if(count(get_posts($args)) > 0):

include("single_opinion.php") ?>

<?php else:
get_header();
$magbook_settings = magbook_get_theme_options();
$magbook_display_page_single_featured_image = $magbook_settings['magbook_display_page_single_featured_image']; ?>
<div class="wrap single-post">
	<div class="row">
		<div class="col-md-9">
			<main id="main" class="site-main" role="main">
			<?php global $magbook_settings;
			while( have_posts() ) {
				$post_id = get_the_ID();
				the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class();?>>
					<?php if(has_post_thumbnail() && $magbook_display_page_single_featured_image == 0 ){ ?>
						<div class="post-image-content">
							<figure class="post-featured-image">
								<?php the_post_thumbnail(); ?>
							</figure>
						</div><!-- end.post-image-content -->
					<?php }
					$magbook_entry_meta_single = $magbook_settings['magbook_entry_meta_single']; ?>
					<header class="entry-header">
						<?php if($magbook_entry_meta_single!='hide'){ ?>
							<div class="entry-meta">
								<?php do_action('magbook_post_categories_list_id'); ?>
							</div>
							<?php } ?>
							<h1 class="entry-title"><?php the_title();?></h1> <!-- end.entry-title -->
							<?php if($magbook_entry_meta_single!='hide'){
								echo  '<div class="entry-meta">';
									echo get_avatar( get_the_author_meta('ID'), $size = '96');
									echo '<span class="author vcard"><a href="'.esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) )).'" title="'.the_title_attribute('echo=0').'"><i class="fa fa-user-o"></i> ' .esc_html(get_the_author()).'</a></span>';
									printf( '<span class="posted-on"><a href="%1$s" title="%2$s"><i class="fa fa-calendar-o"></i> %3$s</a></span>',
													esc_url(get_the_permalink()),
													esc_attr( get_the_time(get_option( 'date_format' )) ),
													esc_html( get_the_time(get_option( 'date_format' )) )
												);
								if ( comments_open()) { ?>
										<span class="comments">
										<?php comments_popup_link( __( '<i class="fa fa-comment-o"></i> No Comments', 'magbook' ), __( '<i class="fa fa-comment-o"></i> 1 Comment', 'magbook' ), __( '<i class="fa fa-comment-o"></i> % Comments', 'magbook' ), '', __( 'Comments Off', 'magbook' ) ); ?> </span>
								<?php }

								$tag_list = get_the_tag_list();
								$format = get_post_format();
								if ( current_theme_supports( 'post-formats', $format ) ) {
									printf( '<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
									sprintf( ''),
									esc_url( get_post_format_link( $format ) ),
									esc_html(get_post_format_string( $format ))
									);
								}
								if(!empty($tag_list)){ ?>
									<span class="tag-links">
										<?php   echo get_the_tag_list(); ?>
									</span> <!-- end .tag-links -->
								<?php }
								echo  '</div> <!-- end .entry-meta -->';
							} ?>
					</header> <!-- end .entry-header -->
					<div class="entry-content">
							<?php the_content(); ?>			
					</div><!-- end .entry-content -->
					<?php wp_link_pages( array( 
						'before'            => '<div style="clear: both;"></div><div class="pagination clearfix">'.esc_html__( 'Pages:', 'magbook' ),
						'after'             => '</div>',
						'link_before'       => '<span>',
						'link_after'        => '</span>',
						'pagelink'          => '%',
						'echo'              => 1
					) ); ?>
				</article>
				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				if ( is_singular( 'attachment' ) ) {
					// Parent post navigation.
					the_post_navigation( array(
								'prev_text' => _x( '<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'magbook' ),
							) );
				} elseif ( is_singular( 'post' ) ) {
				the_post_navigation( array(
						'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'magbook' ) . '</span> ' .
							'<span class="screen-reader-text">' . __( 'Next post:', 'magbook' ) . '</span> ' .
							'<span class="post-title">%title</span>',
						'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'magbook' ) . '</span> ' .
							'<span class="screen-reader-text">' . __( 'Previous post:', 'magbook' ) . '</span> ' .
							'<span class="post-title">%title</span>',
					) );
				}
				$cats = get_the_category(get_the_ID());
				$cat_name = $cats[0]->slug;
				$get_related_posts = new WP_Query( array(
					  	'posts_per_page' 			=> 8,
					  	'post__not_in'	=>	array(get_the_ID()),
					  	'category_name'				=> esc_attr($cat_name),
					  	'post_status'		=>	'publish',
					  	'ignore_sticky_posts'=>	'true'
					  ) ); 
				if ($get_related_posts->have_posts()): ?>

					<h1 class="category-title">You May Like</h1>
					<div class="flex-container">
						<?php 
						while( $get_related_posts->have_posts() ):$get_related_posts->the_post(); ?>
							<article id="post-<?php the_ID(); ?>" <?php post_class();?>>
								<?php if(has_post_thumbnail() ){ ?>
								<div class="cat-box-image">
									<figure class="post-featured-image">
										<a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('magbook-featured-image'); ?></a>
									</figure>
									<!-- end .post-featured-image -->
								</div>
								<?php } ?>
								<!-- end .cat-box-image -->
								<div class="cat-box-text">
									<header class="entry-header">
										<h2 class="entry-title">
											<a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										</h2>
										<!-- end.entry-title -->
									</header>
									<!-- end .entry-header -->
								</div>
							</article>
						<?php 
						endwhile;
					 	wp_reset_postdata();
						?>
					</div>
				<?php
				endif;
				$categories=get_categories(
				    array( 'parent' => 42 )
				);
				$cat_array = array();
				foreach ($categories as $cat) {
					array_push($cat_array, $cat->term_id);
				}
				$get_featured_posts = new WP_Query( array(
					'posts_per_page' 			=> 2,
					'post_status'		=>	'publish',
					'category__not_in' => $cat_array,
					'post__not_in' => array($post_id),
					'tax_query' => array(
					        array(
					            'taxonomy' => 'post_format',
					            'field' => 'slug',
					            'terms' => array( 'post-format-video' ),
					            'operator' => 'NOT IN'
					        )
					    ),
					'ignore_sticky_posts'=>	'true'
				) );
				/*global $post;
			 	$post = get_post($post_id);
			 	$post_id = $post->ID;
			 	$post = get_next_post();*/
			 	while( $get_featured_posts->have_posts() ):$get_featured_posts->the_post(); ?>

				 	<article id="post-<?php the_ID(); ?>" <?php post_class();?>>
					<?php if(has_post_thumbnail() && $magbook_display_page_single_featured_image == 0 ){ ?>
						<div class="post-image-content">
							<figure class="post-featured-image">
								<?php the_post_thumbnail(); ?>
							</figure>
						</div><!-- end.post-image-content -->
					<?php }
					$magbook_entry_meta_single = $magbook_settings['magbook_entry_meta_single']; ?>
						<header class="entry-header">
							<?php if($magbook_entry_meta_single!='hide'){ ?>
								<div class="entry-meta">
									<?php do_action('magbook_post_categories_list_id'); ?>
								</div>
								<?php } ?>
								<h1 class="entry-title"><?php the_title();?></h1> <!-- end.entry-title -->
								<?php if($magbook_entry_meta_single!='hide'){
									echo  '<div class="entry-meta">';
										echo get_avatar( get_the_author_meta('ID'), $size = '96');
										echo '<span class="author vcard"><a href="'.esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) )).'" title="'.the_title_attribute('echo=0').'"><i class="fa fa-user-o"></i> ' .esc_html(get_the_author()).'</a></span>';
										printf( '<span class="posted-on"><a href="%1$s" title="%2$s"><i class="fa fa-calendar-o"></i> %3$s</a></span>',
														esc_url(get_the_permalink()),
														esc_attr( get_the_time(get_option( 'date_format' )) ),
														esc_html( get_the_time(get_option( 'date_format' )) )
													);
									if ( comments_open()) { ?>
											<span class="comments">
											<?php comments_popup_link( __( '<i class="fa fa-comment-o"></i> No Comments', 'magbook' ), __( '<i class="fa fa-comment-o"></i> 1 Comment', 'magbook' ), __( '<i class="fa fa-comment-o"></i> % Comments', 'magbook' ), '', __( 'Comments Off', 'magbook' ) ); ?> </span>
									<?php }

									$tag_list = get_the_tag_list();
									$format = get_post_format();
									if ( current_theme_supports( 'post-formats', $format ) ) {
										printf( '<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
										sprintf( ''),
										esc_url( get_post_format_link( $format ) ),
										esc_html(get_post_format_string( $format ))
										);
									}
									if(!empty($tag_list)){ ?>
										<span class="tag-links">
											<?php   echo get_the_tag_list(); ?>
										</span> <!-- end .tag-links -->
									<?php }
									echo  '</div> <!-- end .entry-meta -->';
								} ?>
						</header> <!-- end .entry-header -->
						<div class="entry-content">
								<?php the_excerpt() ?>			
						</div><!-- end .entry-content -->
					</article>
				<?php
				endwhile;
				wp_reset_postdata();
				?>
				<h2>Trending</h2>
				<?php echo do_shortcode("[wpp range='last7days' limit=8 stats_views=0 stats_date=1 order_by='views']"); ?>
			<?php } ?>
			</main><!-- end #main -->
		</div>
		<div class="col-md-3">
			<?php 
				if( is_active_sidebar('single_post_sidebar')){
					dynamic_sidebar('single_post_sidebar');
				}
			?>
		</div>
	</div>
</div><!-- end .wrap -->
<?php get_footer(); ?>
<?php endif; ?>