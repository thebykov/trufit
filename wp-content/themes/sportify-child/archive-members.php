<?php get_header(); ?>

    <div class="main-content content">

        <div  id="blog_box" class="box blog-box no-margin archive archive-box"><!-- Section Events -->

            <div class="container">

                <header>

                    <h2 class="entry-title text-center">

                        TRUFIT BOOTCAMP MEMBERS LIST

                    </h2>
<hr>
                </header>

                <div class="row">

                    <div class="col-md-12">

                        <?php if (have_posts()) : ?>

                            <ul class="clean-list member-items row">

                                <?php while(have_posts()) : the_post();


                                    get_template_part('content','members');



                                endwhile; ?>

                            </ul>

                            <?php get_template_part('nav','main')?>



                            <?php else: ?>

                                <h2 class="entry-title"><?php _e('No posts to display','sportify'); ?></h2>

                            <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

<?php get_footer(); ?>