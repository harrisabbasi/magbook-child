<?php
/**
* Latest Page Template
*/
 
get_header(); ?> 
 
<div class="wrap category-general">
	<div class="container">
		<header class="archive-header">
		<h1 class="archive-title">Latest News </h1>
		</header>
		 
		 <div class="row">
		 	<div class="col-md-9">
		 		<?php echo do_shortcode("[category_four category=''][/category_four]"); ?>
		 	</div>
		 	<div class="col-md-3">
				<?php echo do_shortcode("[wpp range='last7days' limit=10 stats_views=0 stats_date=1 order_by='views' ]"); ?>
			</div>
		 </div>

		 <?php echo do_shortcode("[more_like_this category='' posts_per_page='14' ][/more_like_this]"); ?>
	</div>
</div>
 
<?php get_footer(); ?>