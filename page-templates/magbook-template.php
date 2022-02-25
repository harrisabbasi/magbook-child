<?php
/**
 * Template Name: Magbook Template
 *
 * Displays Magazine template.
 *
 * @package Theme Freesia
 * @subpackage Magbook
 * @since Magbook 1.0
 */
get_header(); ?>
<div class="wrap">
	<?php 	if( is_active_sidebar( 'magbook_primary_fullwidth' ) && class_exists('Magbook_Plus_Features') ){
		echo '<div class="primary-full-width clearfix">';
			dynamic_sidebar ('magbook_primary_fullwidth');
		echo '</div>';
	} ?>
	<div class="container">
		<div class="row">
			<div class="col-sm-5">
				<?php 
				if( is_active_sidebar( 'new_section_one' )){
					dynamic_sidebar( 'new_section_one' );
				}
				the_content();
				?>
			</div>
			<div class="col-sm-3">
				<?php 
				if( is_active_sidebar( 'new_section_two' )){
					dynamic_sidebar( 'new_section_two' );
				}
				?>
			</div>
			<div class="col-sm-4">
				<?php 
				if( is_active_sidebar( 'new_section_three' )){
					dynamic_sidebar( 'new_section_three' );
				}
				?>
			</div>
		</div>
		<?php echo do_shortcode("[category_one category='Fashion'][/category_one]"); ?>

		<?php echo do_shortcode("[category_two category='Entertainment'][/category_two]"); ?>
	</div>
	
		<?php if( is_active_sidebar( 'magbook_template_sidebar_section' )){ ?>
		<aside id="secondary" class="widget-area" role="complementary" aria-label="<?php esc_attr_e('Side Sidebar','magbook');?>">
			<?php dynamic_sidebar( 'magbook_template_sidebar_section' ); ?>
		</aside> <!-- end #secondary -->
	<?php	}
	if( is_active_sidebar( 'magbook_seondary_fullwidth' ) && class_exists('Magbook_Plus_Features') ){
		echo '<div class="secondary-full-width clearfix">';
			dynamic_sidebar ('magbook_seondary_fullwidth');
		echo '</div>';
	} ?>
	
</div><!-- end .wrap -->


<?php get_footer();