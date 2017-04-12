<?php
/**
 * Search results page
 */
?>

<?php get_header(); ?>
    <div class="main-content content">
        <div  id="blog_box" class="box blog-box margin-top search-results search-box"><!-- Section Events -->
            <div class="container">
                <header>
                    <h2 class="entry-title"><?php _e('Search Results for ','sportify') ?><span><i>'<?php echo get_search_query(); ?>'</i></span> :</h2>
                </header>
                <div class="row">
                    <div class="col-md-8">
                        <?php if (have_posts()) : ?>
                            <ul class="clean-list blog-items row">
                                <?php while(have_posts()) : the_post();

                                    add_filter('the_content', 'cut_shortcodes');
                                    get_template_part('content','blog');
                                endwhile; ?>
                            </ul>
                            <?php get_template_part('nav','main')?>

                            <?php else: ?>
                                <h2 class="entry-title"><?php _e('No matching posts found','sportify'); ?></h2>
                            <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <?php get_sidebar(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>