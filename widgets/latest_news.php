<?php

/**
 * Display Category box widget with layout 1, layout 2 and layout 3
 *
 * @package Theme Freesia
 * @subpackage Magbook
 * @since Magbook 1.0
 */

class Latest_news_widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */

	function __construct() {
		$widget_ops = array( 'classname' => 'widget-cat-box', 'description' => __( 'Displays Latest News', 'magbook') );
		$control_ops = array('width' => 200, 'height' => 250);
		parent::__construct( false, $name=__('Latest News Widget','magbook'), $widget_ops, $control_ops );
	}


	function form($instance) {
		$instance = wp_parse_args(( array ) $instance, array('title' => '','number' => '5','category' => '', 'link'=>''));
		$title    = esc_attr($instance['title']);
		$number = absint( $instance[ 'number' ] );
		$link = esc_url( $instance[ 'link' ] );
		$category = $instance[ 'category' ];
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title');?>">
				<?php _e('Title:', 'magbook');?>
			</label>
			<input id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo esc_attr($title);?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('link');?>">
				<?php _e('Custom Link:', 'magbook');?>
			</label>
			<input id="<?php echo $this->get_field_id('link');?>" name="<?php echo $this->get_field_name('link');?>" type="text" value="<?php echo esc_url($link);?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>">
			<?php _e( 'Number of Post:', 'magbook' ); ?>
			</label>
			<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo absint($number); ?>" size="3" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Select category', 'magbook' ); ?>:</label>
			<?php wp_dropdown_categories( array( 'show_option_none' =>' ','name' => $this->get_field_name( 'category'), 'value_field' => 'name' , 'selected' => $category ) ); ?>
		</p>
		<?php
	}
	function update($new_instance, $old_instance) {

		$instance  = $old_instance;
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['link'] = esc_url_raw($new_instance['link']);
		$instance[ 'number' ] = absint( $new_instance[ 'number' ] );
		$instance[ 'category' ] = wp_kses_post($new_instance[ 'category' ]);
		return $instance;
	}
	function widget($args, $instance) {
		extract($args);
		extract($instance);
		$title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : '';
		$link = isset( $instance[ 'link' ] ) ? $instance[ 'link' ] : '';
		$number = empty( $instance[ 'number' ] ) ? 3 : $instance[ 'number' ];
		$category = isset( $instance[ 'category' ] ) ? $instance[ 'category' ] : '';

		if($category !='-1'){
			$get_featured_posts = new WP_Query( array(
				'posts_per_page' 			=> absint($number),
				'category_name'				=> esc_attr($category),
				'post_status'		=>	'publish',
				'ignore_sticky_posts'=>	'true'
			) );
		} else {
			$get_featured_posts = new WP_Query( array(
				'posts_per_page' 			=> absint($number),
				'post_status'		=>	'publish',
				'ignore_sticky_posts'=>	'true'
			) );

		}

		echo '<!-- Category Box Widget ============================================= -->' .$before_widget;
		?>
			<div class="box-layout">
			<?php
			if ( $title!='' || $link!='' ){ ?>
				<h2 class="widget-title">
					<?php if ( $title != '' ){ ?>
						<span><?php echo esc_html($title); ?></span>
					<?php } 
					if ( $link != '' ){ ?>
					
					<a href="<?php echo esc_url($link);?>" class="more-btn"><?php echo esc_html($title); ?></a>
					<?php } ?>
				</h2><!-- end .widget-title -->
			<?php	} ?>
				<div class="cat-box-wrap clearfix">
					<?php
					$i = 1;
					while( $get_featured_posts->have_posts() ):$get_featured_posts->the_post(); 
					?>
					<div class="latest-news image-text">
						<?php if ($i == 1){ ?>
	 						<article id="post-<?php the_ID(); ?>" <?php post_class();?>>
								<?php if(has_post_thumbnail() ){ ?>
								<div class="cat-box-image">
									<figure class="post-featured-image">
										<a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large-image'); ?></a>
									</figure>
									<!-- end .post-featured-image -->
								</div>
								<!-- end .cat-box-image -->
								<?php } 
								$cats = get_the_category(get_the_ID());
								$human_time = human_time_diff(get_the_time('U'), current_time ('timestamp'));
								?>
								<p class="cat-info"><?php echo $cats[0]->name. ' / '.$human_time ?></p>
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
						<?php } else {
						?>
							<article id="post-<?php the_ID(); ?>" <?php post_class();?>>
								<?php if(has_post_thumbnail() ){ ?>
								<div class="cat-box-image">
									<figure class="post-featured-image">
										<a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium-image'); ?></a>
									</figure>
									<!-- end .post-featured-image -->
								</div>
								<!-- end .cat-box-image -->
								<?php } 
								$cats = get_the_category(get_the_ID());
								$human_time = human_time_diff(get_the_time('U'), current_time ('timestamp'));
								?>
								<p class="cat-info"><?php echo $cats[0]->name. ' / '.$human_time ?></p>
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
						<?php } ?>
					</div>
						<?php 
					$i++;
					endwhile;
					wp_reset_postdata(); 

					?>
				</div>
				<!-- end .cat-box-wrap -->
			</div>
			<!-- end .box-layout-1 -->
	<?php echo $after_widget.'<!-- end .widget-cat-box -->';
	}
}