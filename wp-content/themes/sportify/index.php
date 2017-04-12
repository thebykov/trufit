<?php
/*
 * Single post page
 */
?>

<?php get_header(); ?>

<div class="main-content content">
    <div  id="blog_box" class="box blog post-box margin-top"><!-- Section Blog Post -->
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <?php if (have_posts()) :   ?>
                        <ul class="clean-list blog-items row">
                            <?php while(have_posts()) : the_post();
                                get_template_part('content','blog');
                            endwhile; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                <div class="col-md-4">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        </div><!-- Container -->
    </div>
</div>
<?php get_footer(); ?>