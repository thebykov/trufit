<?php get_header(); ?>

    <div class="main-content content">

        <div  id="blog_box" class="box blog-box no-margin archive archive-box"><!-- Section Events -->

            <div class="container">

                <header>

                    <h2 class="entry-title" style="text-align: center;">TRU FIT Workouts</h2>
<?php if(!is_user_logged_in()): ?>
<div style="text-align: center;color: #fff;margin-bottom: 30px;font-size: 16px;">Don't see any content? <a id="login-modal" style="color: #d93;font-weight: bold;" href="/login">CLICK HERE TO LOGIN</a></div>
<?php endif; ?>

                </header>
<?php if((current_user_can('special_member') || current_user_can('owner') || current_user_can('administrator'))): ?>
                <div class="row">

                    <div class="col-md-8">

                        <?php if (have_posts()) : ?>

                            <ul class="clean-list blog-items row">

                                <?php while(have_posts()) : the_post();



                                    get_template_part('content','blog-wod');



                                endwhile; ?>

                            </ul>

                            <?php get_template_part('nav','main')?>



                            <?php else: ?>

                                <h2 class="entry-title"><?php _e('No posts to display','sportify'); ?></h2>

                            <?php endif; ?>

                    </div>

                    <div class="col-md-4">
                        <div class="main-sidebar right-sidebar grey-background wod-sidebar">
                            <?php dynamic_sidebar('wod-sidebar');?>
                        </div>
                    </div>

<?php else: ?>
<br><br>
<h2 style="color: #fff;">Special Access is required to see this content. <br>Please contact us if you believe this is a mistake.</h2>
<br><br>
<?php endif; ?>

                </div>

            </div>

        </div>

    </div>

<?php get_footer(); ?>