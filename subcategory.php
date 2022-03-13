<?php
/**
* Template for Subcategory
*/
 
get_header(); ?> 
 
<div class="wrap">
	<div class="container">
		<header class="archive-header">
		<h1 class="archive-title"><?php single_cat_title( '', true ); ?></h1>
		 
		 
		<?php
		// Display optional category description
		 if ( category_description() ) : ?>
			<div class="archive-meta"><?php echo category_description(); ?></div>
		<?php endif;
		$term = get_queried_object();
		$image = get_field('category_image', $term);?>
		<img width="150px" src="<?php echo $image['url'] ?>">
		</header>
		 
		 <div class="row">
		 	<div class="col-sm-9">
		 		<?php echo do_shortcode("[more_like_this category=" .$wp_query->get_queried_object()->slug . " posts_per_page='14' paged='1' title='Articles'][/more_like_this]"); ?>
		 		
		 	</div>
		 	<div class="col-sm-3">
				
			</div>
		 </div>

		 
	</div>
</div>
 
<?php get_footer(); ?>