<?php get_header(); ?>

    <div class="main-content content">

        <div  id="blog_box" class="box blog-box no-margin archive archive-box"><!-- Section Events -->


                        <?php if (have_posts()) : ?>

                            <ul class="clean-list blog-items row">

                               <?php
																		
								include('theme_config/views/team-single-view.php');

                                ?>

                            </ul>

                            <?php get_template_part('nav','main')?>



                            <?php else: ?>

                                <h2 class="entry-title"><?php _e('No posts to display','sportify'); ?></h2>

                            <?php endif; ?>



        </div>

    </div>

<?php get_footer(); ?>