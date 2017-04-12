<?php get_header(); ?>
    <div class="main-content content">
        <div  id="blog_box" class="box blog-box margin-top tag-archive archive-box"><!-- Section Events -->
            <div class="container">
                <header>
                    <h2 class="entry-title"><?php _e('Tag Filter: ','sportify'); echo '<span>'.single_tag_title('', false).'</span>'; ?></h2>
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
                                <h2 class="entry-title"><?php _e('No posts to display in ','sportify'); echo single_tag_title( '<span>', false ); ?></h2>
                            <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <div class="main-sidebar right-sidebar grey-background wod-sidebar">
                        <?php dynamic_sidebar('wod-sidebar');?>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>