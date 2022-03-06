<?php
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

function my_theme_enqueue_styles() {
    $parenthandle = 'magbook-style';
    $theme = wp_get_theme();
    wp_enqueue_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css');
    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css', 
        array(),  // if the parent theme code has a dependency, copy it to here
        $theme->parent()->get('Version')
    );
    wp_enqueue_style( 'child-style', get_stylesheet_uri(),
        array( $parenthandle ),
        $theme->get('Version') // this only works if you have Version in the style header
    );
}

add_action('widgets_init', 'widgets_new_init');
function widgets_new_init() {

    register_sidebar(array(
            'name' => __('New Section One', 'magbook'),
            'id' => 'new_section_one',
            'description' => __('The initial section of homepage', 'magbook'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));

    register_sidebar(array(
            'name' => __('New Section Two', 'magbook'),
            'id' => 'new_section_two',
            'description' => __('The initial section of homepage', 'magbook'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));

    register_sidebar(array(
            'name' => __('New Section Three', 'magbook'),
            'id' => 'new_section_three',
            'description' => __('The initial section of homepage', 'magbook'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));

    register_sidebar(array(
            'name' => __('Single Post Sidebar', 'magbook'),
            'id' => 'single_post_sidebar',
            'description' => __('The sidebar on single posts', 'magbook'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));

    register_widget( 'Latest_news_widget' );
    register_widget( 'Three_tabs' );
}

require get_stylesheet_directory(). '/widgets/latest_news.php';
require get_stylesheet_directory(). '/widgets/three_tabs.php';

/**
 * Builds custom HTML.
 *
 * With this function, I can alter WPP's HTML output from my theme's functions.php.
 * This way, the modification is permanent even if the plugin gets updated.
 *
 * @param  array $popular_posts
 * @param  array $instance
 * @return string
 */
function my_custom_popular_posts_html_list($popular_posts, $instance) {
    $output = '<ol class="wpp-list">';
    $i = 1;

    // loop the array of popular posts objects
    foreach( $popular_posts as $popular_post ) {

        $stats = array(); // placeholder for the stats tag

        // Comment count option active, display comments
        if ( $instance['stats_tag']['comment_count'] ) {
            // display text in singular or plural, according to comments count
            $stats[] = '<span class="wpp-comments">' . sprintf(
                _n('1 comment', '%s comments', $popular_post->comment_count, 'wordpress-popular-posts'),
                number_format_i18n($popular_post->comment_count)
            ) . '</span>';
        }

        // Pageviews option checked, display views
        if ( $instance['stats_tag']['views'] ) {

            // If sorting posts by average views
            if ($instance['order_by'] == 'avg') {
                // display text in singular or plural, according to views count
                $stats[] = '<span class="wpp-views">' . sprintf(
                    _n('1 view per day', '%s views per day', intval($popular_post->pageviews), 'wordpress-popular-posts'),
                    number_format_i18n($popular_post->pageviews, 2)
                ) . '</span>';
            } else { // Sorting posts by views
                // display text in singular or plural, according to views count
                $stats[] = '<span class="wpp-views">' . sprintf(
                    _n('1 view', '%s views', intval($popular_post->pageviews), 'wordpress-popular-posts'),
                    number_format_i18n($popular_post->pageviews)
                ) . '</span>';
            }
        }

        // Author option checked
        if ( $instance['stats_tag']['author'] ) {
            $author = get_the_author_meta('display_name', $popular_post->uid);
            $display_name = '<a href="' . get_author_posts_url($popular_post->uid) . '">' . $author . '</a>';
            $stats[] = '<span class="wpp-author">' . sprintf(__('by %s', 'wordpress-popular-posts'), $display_name). '</span>';
        }

        // Category option checked
        if ( $instance['stats_tag']['taxonomy'] ) {
            $post_cat = get_the_category($popular_post->id);
            $post_cat = ( isset($post_cat[0]) )
              ? '<a href="' . get_category_link($post_cat[0]->term_id) . '">' . $post_cat[0]->cat_name . '</a>'
              : '';

            if ( $post_cat != '' ) {
                $stats[] = '<span class="wpp-category">' . sprintf(__('%s', 'wordpress-popular-posts'), $post_cat) . '</span>';
            }
        }

        // Date option checked
        if ( $instance['stats_tag']['date']['active'] ) {
            $date = human_time_diff(strtotime($popular_post->date), current_time('timestamp'));
            $stats[] = '<span class="wpp-date">' . sprintf(__('%s ago', 'wordpress-popular-posts'), $date) . '</span>';
        }

        // Build stats tag
        if ( ! empty($stats) ) {
            $stats = '<div class="wpp-stats">' . join(' / ', $stats) . '</div>';
        } else {
            $stats = null;
        }

        $excerpt = ''; // Excerpt placeholder

        // Excerpt option checked, build excerpt tag
        if ( $instance['post-excerpt']['active'] ) {

            $excerpt = get_excerpt_by_id($popular_post->id);
            if ( ! empty($excerpt) ) {
                $excerpt = '<div class="wpp-excerpt">' . $excerpt . '</div>';
            }

        }

        $output .= "<li>";
        $output .= get_the_post_thumbnail($popular_post->id);
        $output .= $stats;
        $output .= "<div class='box'><span>" . $i . "." . "</span>";
        $output .= "<h2 class=\"entry-title\"><a href=\"" . get_permalink($popular_post->id) . "\" title=\"" . esc_attr($popular_post->title) . "\">" . $popular_post->title . "</a></h2>";
        $output .= "<div class='clear'></div></div>";
        $output .= $excerpt;
        $output .= "</li>" . "\n";

        $i++;
    }

    $output .= '</ol>';

    return $output;
}
add_filter('wpp_custom_html', 'my_custom_popular_posts_html_list', 10, 2);

/**
 * The [category_one] shortcode.
 *
 * Displays a category posts with a specific layout
 *
 * @param array  $atts    Shortcode attributes. Default empty.
 * @param string $content Shortcode content. Default null.
 * @param string $tag     Shortcode tag (name). Default empty.
 * @return string Shortcode output.
 */
function shortcode_one( $atts = [], $content = null, $tag = '' ) {
    // normalize attribute keys, lowercase
    $atts = array_change_key_case( (array) $atts, CASE_LOWER );
 
    // override default attributes with user attributes
    $category_atts = shortcode_atts(
        array(
            'category' => '',
        ), $atts, $tag
    );

    if ($category_atts['category'] != ""){
        $args = array( 'ignore_sticky_posts' => 1, 'posts_per_page' => 6, 'post_status' => 'publish',
                        'category_name' => $category_atts['category']);
        $posts = new WP_Query( $args );
    }
    ?>
    <div class="category-one">
        <h1 class="category-title"><?php echo $category_atts['category'] ?></h1>
        <div class="container-one">
        <?php
        $i = 1;
        while( $posts->have_posts() ): $posts->the_post();
            if ($i == 1){ ?>
                <div class="post-one float-right image-text">
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
                </div>
                <div class="post-two float-left">
            <?php
            } ?>
                
            <?php if ($i > 1 && $i < 4){ ?>
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
            <?php } 
            if ($i == 3){ ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="container-2 mb-popular">
        <?php }
            if ($i > 3){ ?>
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
                
        <?php }
        $i++;
        endwhile;
        wp_reset_postdata();
        ?>
        </div>
        <div class="clear"></div>
    </div>
    <?php

}
 
add_shortcode( 'category_one', 'shortcode_one' );


/**
 * The [category_two] shortcode.
 *
 * Displays a category posts with a specific layout
 *
 * @param array  $atts    Shortcode attributes. Default empty.
 * @param string $content Shortcode content. Default null.
 * @param string $tag     Shortcode tag (name). Default empty.
 * @return string Shortcode output.
 */
function shortcode_two( $atts = [], $content = null, $tag = '' ) {
    // normalize attribute keys, lowercase
    $atts = array_change_key_case( (array) $atts, CASE_LOWER );
 
    // override default attributes with user attributes
    $category_atts = shortcode_atts(
        array(
            'category' => '',
        ), $atts, $tag
    );

    if ($category_atts['category'] != ""){
        $args = array( 'ignore_sticky_posts' => 1, 'posts_per_page' => 6, 'post_status' => 'publish',
                        'category_name' => $category_atts['category']);
        $posts = new WP_Query( $args );
    }
    ?>
    <div class="category-two">
        <h1 class="category-title"><?php echo $category_atts['category'] ?></h1>
        <div class="container-three">
        <?php
        $i = 1;
        while( $posts->have_posts() ): $posts->the_post();
            if ($i == 1 || $i == 2){ ?>
                <div class="post-half image-text">
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
                </div>
            <?php
            } ?>
                
            <?php if ($i > 2){ ?>
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
            <?php } ?>
            
                
        <?php
        $i++;
        endwhile;
        wp_reset_postdata();
        ?>
        </div>
    </div>
    <?php

}
 
add_shortcode( 'category_two', 'shortcode_two' );

/**
 * The [videos] shortcode.
 *
 * Displays videos with a specific layout
 *
 * @param array  $atts    Shortcode attributes. Default empty.
 * @param string $content Shortcode content. Default null.
 * @param string $tag     Shortcode tag (name). Default empty.
 * @return string Shortcode output.
 */
function shortcode_three( $atts = [], $content = null, $tag = '' ) {
    
    
    $args = array(
        'post_type'=> 'post',
        'post_status' => 'publish',
        'posts_per_page' => 5,
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
    
    ?>
    <div class="videos">
        <h1 class="category-title">Videos</h1>
        <?php
        $i = 1;
        while( $videos->have_posts() ): $videos->the_post();
            if ($i == 1){ ?>
                <div class="video-one float-left image-text">
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
                </div>
                <div class="video-two float-right mb-popular">
            <?php
            } ?>
                
            <?php if ($i > 1){ ?>
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
                
        <?php }
        $i++;
        endwhile;
        wp_reset_postdata();
        ?>
        </div>
        <div class="clear"></div>
    </div>
    <?php

}
 
add_shortcode( 'videos', 'shortcode_three' );

/**
 * The [category_three] shortcode.
 *
 * Displays a category posts with a specific layout
 *
 * @param array  $atts    Shortcode attributes. Default empty.
 * @param string $content Shortcode content. Default null.
 * @param string $tag     Shortcode tag (name). Default empty.
 * @return string Shortcode output.
 */
function shortcode_four( $atts = [], $content = null, $tag = '' ) {
    // normalize attribute keys, lowercase
    $atts = array_change_key_case( (array) $atts, CASE_LOWER );
 
    // override default attributes with user attributes
    $category_atts = shortcode_atts(
        array(
            'category' => '',
        ), $atts, $tag
    );

    if ($category_atts['category'] != ""){
        $args = array( 'ignore_sticky_posts' => 1, 'posts_per_page' => 6, 'post_status' => 'publish',
                        'category_name' => $category_atts['category']);
        $posts = new WP_Query( $args );
    }
    ?>
    <div class="category-one">
        <h1 class="category-title"><?php echo $category_atts['category'] ?></h1>
        <div class="container-one">
        <?php
        $i = 1;
        while( $posts->have_posts() ): $posts->the_post();
            if ($i == 1){ ?>
                <div class="post-three float-left image-text">
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
                </div>
                <div class="post-four float-right">
            <?php
            } ?>
                
            <?php if ($i > 1 && $i < 4){ ?>
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
            <?php } 
            if ($i == 3){ ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="container-2 mb-popular">
        <?php }
            if ($i > 3){ ?>
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
                
        <?php }
        $i++;
        endwhile;
        wp_reset_postdata();
        ?>
        </div>
        <div class="clear"></div>
    </div>
    <?php

}
 
add_shortcode( 'category_three', 'shortcode_four' );

/**
 * The [category_four] shortcode.
 *
 * Displays a category posts with a specific layout
 *
 * @param array  $atts    Shortcode attributes. Default empty.
 * @param string $content Shortcode content. Default null.
 * @param string $tag     Shortcode tag (name). Default empty.
 * @return string Shortcode output.
 */
function shortcode_five( $atts = [], $content = null, $tag = '' ) {
    // normalize attribute keys, lowercase
    $atts = array_change_key_case( (array) $atts, CASE_LOWER );
 
    // override default attributes with user attributes
    $category_atts = shortcode_atts(
        array(
            'category' => '',
        ), $atts, $tag
    );

    if ($category_atts['category'] != ""){
        $args = array( 'ignore_sticky_posts' => 1, 'posts_per_page' => 14, 'post_status' => 'publish',
                        'category_name' => $category_atts['category']);
        $posts = new WP_Query( $args );
    }
    ?>
    <div class="category-four">
        <div class="container-one float-right">
        <?php
        $i = 1;
        while( $posts->have_posts() ): $posts->the_post();
            if ($i == 1){ ?>
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
                <div class="mb-popular">
            <?php
            } ?>
                
            <?php if ($i > 1 && $i < 6){ ?>
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
            <?php } 
            if ($i == 5){ ?>
                </div>
            </div>
            <div class="container-four mb-popular float-left">
        <?php }
            if ($i > 5){ ?>
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
                
        <?php }
        $i++;
        endwhile;
        wp_reset_postdata();
        ?>
        <?php if ($i > 5) { ?>
        </div>
        <div class="clear"></div>
        <?php } else { ?>
        </div>
    </div>
    <div class="clear hot"></div>
        <?php } ?>
    </div>
    <?php

}
 
add_shortcode( 'category_four', 'shortcode_five' );

/**
 * The [more_like_this] shortcode.
 *
 * Displays additional posts
 *
 * @param array  $atts    Shortcode attributes. Default empty.
 * @param string $content Shortcode content. Default null.
 * @param string $tag     Shortcode tag (name). Default empty.
 * @return string Shortcode output.
 */
function shortcode_six( $atts = [], $content = null, $tag = '' ) {
    // normalize attribute keys, lowercase
    $atts = array_change_key_case( (array) $atts, CASE_LOWER );
 
    // override default attributes with user attributes
    $category_atts = shortcode_atts(
        array(
            'category' => '',
            'posts_per_page' => 10
        ), $atts, $tag
    );

    if ($category_atts['category'] != ""){
        $args = array( 'ignore_sticky_posts' => 1, 'posts_per_page' => $category_atts['posts_per_page'], 'paged' => 2, 'post_status' => 'publish', 'category_name' => $category_atts['category']);
        $posts = new WP_Query( $args );
    }
    else{
        $args = array( 'ignore_sticky_posts' => 1, 'posts_per_page' => $category_atts['posts_per_page'], 'paged' =>2, 'post_status' => 'publish');
        $posts = new WP_Query( $args );
    }

    ?>
    <?php if ($posts->have_posts()) { ?>
    <div class="more-like-this mb-popular">
        <h1 class="category-title">More Like This</h1>
        <?php
        while( $posts->have_posts() ): $posts->the_post(); ?>
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
        ?>
        <button class="btn btn-primary" id="load-more" data-page="3" data-posts="<?php echo $category_atts['posts_per_page'] ?>" data-category="<?php echo $category_atts['category'] ?>" >Load More Posts</button>
        <?php wp_nonce_field( 'more_posts_nonce_action', 'more_posts_nonce' ); ?>
    </div>
    <?php }

}
 
add_shortcode( 'more_like_this', 'shortcode_six' );

function magbook_ajax_enqueue_scripts() {
    $theme_version = wp_get_theme()->get( 'Version' );
    $script_handle = 'magbook-ajax';

    wp_enqueue_script( $script_handle, get_stylesheet_directory_uri() . '/js/index.js',
        array( 'jquery' ),
        $theme_version,
        false
    );

    $ajaxurl = admin_url( 'admin-ajax.php');

    wp_localize_script( $script_handle, 'magbookAjaxLocalization', array(
        'ajaxurl' => $ajaxurl,
        'action' => 'magbook_ajax_more_post',
        'noPosts' => esc_html__('No older posts found', 'magbook-child'),
    ) );
}

add_action( 'wp_enqueue_scripts', 'magbook_ajax_enqueue_scripts' );


function magbook_ajax_more_post_ajax() {
    if ( ! isset( $_POST['morePostsNonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['morePostsNonce'] ), 'more_posts_nonce_action' ) ) {
        return wp_send_json_error( esc_html__( 'Number not only once is invalid', 'magbook-child'), 404 );
    }

    $posts_per_page = ! empty( $_POST['postsPerPage'] ) ? (int) $_POST['postsPerPage'] : 10;
    $page = ! empty( $_POST['paged'] ) ? (int) $_POST['paged'] : 1;
    $category = ! empty( $_POST['category'] ) ? sanitize_text_field( wp_unslash( $_POST['category'] ) ) : '';

    $query_args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
    );

    if ( ! empty( $category ) ) {
        $query_args['cat'] = $category;
    }

    $posts_query = new WP_Query( $query_args );

    $posts_out = '';

    ob_start();
    if ($posts_query->have_posts()) {
        while ($posts_query->have_posts()) {
            $posts_query->the_post(); ?>

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
        }
        ?>
        <button class="btn btn-primary" id="load-more" data-page="<?php echo $page + 1 ?>" data-posts="<?php echo $posts_per_page ?>" data-category="<?php echo $category ?>" >Load More Posts</button>
        <?php
    }

    $posts_out = ob_get_clean();

    wp_reset_postdata();

    wp_send_json_success( $posts_out, 200 );

}

add_action( 'wp_ajax_nopriv_magbook_ajax_more_post', 'magbook_ajax_more_post_ajax' );
add_action( 'wp_ajax_magbook_ajax_more_post', 'magbook_ajax_more_post_ajax' );

// Replaces the excerpt "Read More" text by a link
function new_excerpt_more($more) {
       global $post;
    return '<a class="moretag" href="'. get_permalink($post->ID) . '">Continue Reading</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

function child_theme_setup() {
    // override parent theme's 'more' text for excerpts
    remove_filter( 'excerpt_more', 'magbook_continue_reading' ); 
}
add_action( 'after_setup_theme', 'child_theme_setup' );
?>