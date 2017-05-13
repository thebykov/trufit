<?php
/*
 * Single post page
 */
?>

<?php get_header(); ?>

<div class="main-content content">
    <section  id="blog_box" class="box blog post-box margin-top"><!-- Section Blog Post -->
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <?php if (have_posts()) : 
                        while(have_posts()) : the_post();

                            get_template_part('content','single-recipe');

                        endwhile; ?>
                    <?php endif; ?>
                </div>
                <div class="col-md-4">
                    <div class="main-sidebar right-sidebar grey-background wod-sidebar">
                        <?php dynamic_sidebar('recipes-sidebar');?>
                    </div>
                </div>
            </div>
        </div><!-- Container -->
    </section>
</div>
<?php get_footer(); ?>