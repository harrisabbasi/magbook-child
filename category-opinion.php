<?php
/**
* Template for Opinion Category
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
		 		<?php $id = $wp_query->get_queried_object_id();
		 		$args = array('parent' => $id, 'hide_empty'  => false);
		 		$subcategories = get_categories($args);
		 		?>
		 		<div class="flex-container">
		 			<?php foreach($subcategories as $category):
		 				$image = get_field('category_image', $category);?>
		 				<div class="subcategory">
		 					<a href="<?php echo  get_category_link( $category->term_id ) ?>">
		 						<img src="<?php echo $image['url'] ?>">
		 						<h2><?php echo $category->name ?></h2>
		 					</a>
		 				</div>
		 			<?php endforeach; ?>
		 		</div>
		 	</div>
		 	<div class="col-sm-3">
				
			</div>
		 </div>

		 
	</div>
</div>
 
<?php get_footer(); ?>