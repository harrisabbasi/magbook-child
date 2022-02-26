<?php
/**
* A Simple Category Template
*/
 
get_header(); ?> 
 
<div class="wrap">
	<div class="container">
		<header class="archive-header">
		<h1 class="archive-title">Category: <?php single_cat_title( '', true ); ?></h1>
		 
		 
		<?php
		// Display optional category description
		 if ( category_description() ) : ?>
			<div class="archive-meta"><?php echo category_description(); ?></div>
		<?php endif; ?>
		</header>
		 
		 <div class="row">
		 	<div class="col-sm-9">
		 		<?php echo do_shortcode("[category_four category=" . single_cat_title('', false) . "][/category_four]"); ?>
		 	</div>
		 	<div class="col-sm-3">
				<?php echo do_shortcode("[wpp range='last7days' limit=10 stats_views=0 stats_date=1 order_by='views' cat=" . $wp_query->get_queried_object_id() . "]"); ?>
			</div>
		 </div>
	</div>
</div>
 
<?php get_footer(); ?>