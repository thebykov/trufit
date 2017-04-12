<?php
/*
   Template Name: Classes
*/
?>
<?php get_header(); ?>
    <div class="content" id="is_classes">

        <?php if (have_posts()) : 
            while(have_posts()) : the_post();

                the_content();

            endwhile; ?>
        <?php endif; ?>

    </div>
<?php get_footer(); ?>