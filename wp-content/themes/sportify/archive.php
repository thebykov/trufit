<?php get_header(); ?>
    <div class="main-content content">
        <div  id="blog_box" class="box blog-box margin-top archive archive-box"><!-- Section Events -->
            <div class="container">
                <header>
                    <h2 class="entry-title">
                        <?php if (is_day()) : ?>
                            <?php _e('Archive: ','sportify'); echo '<span>'.get_the_date('D M Y').'</span>'; ?>
                        <?php elseif (is_month()) : ?>
                            <?php _e('Archive: ','sportify'); echo '<span>'.get_the_date('M Y').'</span>'; ?>
                        <?php elseif (is_year()) : ?>
                            <?php _e('Archive: ','sportify'); echo '<span>'.get_the_date('Y').'</span>'; ?>
                        <?php else : ?>
                            <?php _e('Archive: ','sportify'); ?>
                        <?php endif; ?>
                    </h2>
                </header>
                <div class="row">
                    <div class="col-md-8">
                        <?php if (have_posts()) : ?>
                            <ul class="clean-list blog-items row">
                                <?php while(have_posts()) : the_post();

                                    get_template_part('content','blog');

                                endwhile; ?>
                            </ul>
                            <?php get_template_part('nav','main')?>

                            <?php else: ?>
                                <h2 class="entry-title"><?php _e('No posts to display','sportify'); ?></h2>
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