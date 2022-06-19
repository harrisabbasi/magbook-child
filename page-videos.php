<?php
/**
* Latest Page Template
*/
 
get_header(); ?> 
 
<div class="wrap category-general">
	<div class="container">
		<header class="archive-header">
		</header>
		 
		 <div class="row">
		 	<div class="col-md-12">
		 		<?php echo do_shortcode("[videos][/videos]"); ?>
			 	<?php
			 	$args = array(
			 	    'post_type'=> 'post',
			 	    'post_status' => 'publish',
			 	    'posts_per_page' => 20,
			 	    'offset' => 5,
			 	    'order' => 'DESC',
			 	    'tax_query' => array(
			 	        array(
			 	            'taxonomy' => 'post_format',
			 	            'field' => 'slug',
			 	            'terms' => array( 'post-format-video' )
			 	        )
			 	    )
			 	);
			 	$videos = new WP_Query( $args );?>

			 	<div class="videos-2">
			 	    <?php
			 	    while( $videos->have_posts() ): $videos->the_post();
			 	    ?>
		 	            <div class="post-quarter">
		                    <article id="post-<?php the_ID(); ?>" <?php post_class();?>>
		                        <?php if(has_post_thumbnail() ){ ?>
		                            <div class="cat-box-image">
		                                <figure class="post-featured-image">
		                                    <a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('magbook-featured-image'); ?></a>
		                                    </figure>
		                                            <!-- end .post-featured-image -->
		                            </div>
		                        <?php }
		                            $cats = get_the_category(get_the_ID());
		                            $human_time = human_time_diff(get_the_time('U'), current_time ('timestamp'));
		                            ?>
		                            <p><?php echo $cats[0]->name. ' / '.$human_time ?></p>
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
	                	</div>
			 	            
			 	    <?php
			 	    endwhile;
			 	    wp_reset_postdata();
			 	    ?>
			 	</div>
			</div>
		 </div>

	</div>
</div>
 
<?php get_footer(); ?>