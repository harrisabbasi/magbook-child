<?php

/**
 * Display Three Tabs 
 *
 * @package Theme Freesia
 * @subpackage Magbook
 * @since Magbook 1.0
 */

class Three_tabs extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */

	function __construct() {
		$widget_ops = array( 'classname' => 'widget-tab-box', 'description' => __( 'Displays three tabs', 'magbook') );
		$control_ops = array('width' => 200, 'height' => 250);
		parent::__construct( false, $name=__('Displays three tabs','magbook'), $widget_ops, $control_ops );
	}


	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 */
	public function form( $instance ) {
		$magbook_latest_posts = ! empty( $instance['magbook_latest_posts'] ) ? absint( $instance['magbook_latest_posts'] ) : 5;
		$magbook_videos = ! empty( $instance['magbook_videos'] ) ? absint( $instance['magbook_videos'] ) : 5; ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'magbook_latest_posts' ); ?>"><?php esc_html_e( 'Number of latest posts:', 'magbook' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'magbook_latest_posts' ); ?>" name="<?php echo $this->get_field_name( 'magbook_latest_posts' ); ?>" type="text" value="<?php echo esc_attr( $magbook_latest_posts ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'magbook_videos' ); ?>"><?php esc_html_e( 'Number of videos:', 'magbook' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'magbook_videos' ); ?>" name="<?php echo $this->get_field_name( 'magbook_videos' ); ?>" type="text" value="<?php echo esc_attr( $magbook_videos ); ?>">
		</p>
		
		<?php 
	}



	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['magbook_latest_posts'] = ( ! empty( $new_instance['magbook_latest_posts'] ) ) ? absint( $new_instance['magbook_latest_posts'] ) : '';
		$instance['magbook_videos'] = ( ! empty( $new_instance['magbook_videos'] ) ) ? absint( $new_instance['magbook_videos'] ) : '';

		return $instance;
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 */
	public function widget( $args, $instance ) {
		extract($args);
		$magbook_latest_posts = ( ! empty( $instance['magbook_latest_posts'] ) ) ? absint( $instance['magbook_latest_posts'] ) : 5;
		$magbook_videos = ( ! empty( $instance['magbook_videos'] ) ) ? absint( $instance['magbook_videos'] ) : 5;

		echo $before_widget; ?>
		<div class="tab-wrapper">
			<div class="tab-menu">
				<button class="active" type="button"><?php esc_html_e( 'Latest', 'magbook' ); ?></button>
				<button type="button"><?php esc_html_e( 'Videos', 'magbook' ); ?></button>
				<button type="button"><?php esc_html_e( 'Opinions', 'magbook' ); ?></button>
			</div>
			<div class="tabs-container ">
				<div class="tab-content">
					<div class="mb-popular tab-latest">
						<?php 
							$args = array( 'ignore_sticky_posts' => 1, 'posts_per_page' => $magbook_latest_posts, 'post_status' => 'publish');
							$popular = new WP_Query( $args );

							if ( $popular->have_posts() ) :

							while( $popular-> have_posts() ) : $popular->the_post(); ?>
								<div <?php post_class('mb-post');?>>
									<?php if ( has_post_thumbnail() ) { ?>
										<figure class="mb-featured-image">
											<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('magbook-featured-image'); ?></a>
										</figure> <!-- end.post-featured-image -->
									<?php } ?>
									<div class="mb-content">
										<?php
										$cats = get_the_category(get_the_ID());
										$human_time = human_time_diff(get_the_time('U'), current_time ('timestamp'));
										?>
										<p><?php echo $cats[0]->name. ' / '.$human_time ?></p>

										<?php the_title( sprintf( '<h3 class="mb-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
										
									</div> <!-- end .mb-content -->
								</div><!-- end .mb-post -->
							<?php
							endwhile;
							wp_reset_postdata();
							endif;
						?>
					</div> <!-- end .mb-popular -->
				</div><!-- end .tab-content -->
				<div class="tab-content">
					<div class="mb-popular">
						<?php
						$args = array(
						    'post_type'=> 'post',
						    'post_status' => 'publish',
						    'posts_per_page' => $magbook_videos,
						    'order' => 'DESC',
						    'tax_query' => array(
						        array(
						            'taxonomy' => 'post_format',
						            'field' => 'slug',
						            'terms' => array( 'post-format-video' )
						        )
						    )
						);
						$videos = new WP_Query( $args );

							if ( $videos->have_posts() ) :

							while( $videos-> have_posts() ) : $videos->the_post(); ?>
								<div <?php post_class('mb-post');?>>
									<?php if ( has_post_thumbnail() ) { ?>
										<figure class="mb-featured-image">
											<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('magbook-featured-image'); ?></a>
										</figure> <!-- end.post-featured-image -->
									<?php } ?>
									<div class="mb-content">
										<?php
										$cats = get_the_category(get_the_ID());
										$human_time = human_time_diff(get_the_time('U'), current_time ('timestamp'));
										?>
										<p><?php echo $cats[0]->name. ' / '.$human_time ?></p>

										<?php the_title( sprintf( '<h3 class="mb-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
										
									</div> <!-- end .mb-content -->
								</div><!-- end .mb-post -->
							<?php
							endwhile;
							wp_reset_postdata();
							endif;
							?>
					</div> <!-- end .mb-comments -->
				</div><!-- end .tab-content -->
				<div class="tab-content">
					<div class="mb-tag-cloud">
						<div class="mb-tags">
							<?php        
								$tags = get_tags();             
								if($tags) {               
									foreach ( $tags as $tag ): ?>    
										<a href="<?php echo esc_url( get_term_link( $tag ) ); ?>"><?php echo esc_html( $tag->name ); ?></a>      
										<?php     
									endforeach;       
								} else {          
									esc_html_e( 'No tags created.', 'magbook');           
								}            
							?>
						</div>
					</div>
					<!-- end .widget_tag_cloud -->		
				</div><!-- end .tab-content -->
			</div><!-- end .tabs-container -->
		</div> <!-- end .tab-wrapper -->
		<?php echo $after_widget;

	}

}