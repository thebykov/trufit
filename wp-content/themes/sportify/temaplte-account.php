<?php
/*
 * Template Name: Woocommerce Account
 */
?>

<?php 
    get_header('shop');
?>

<div class="container"> 
    <?php if (have_posts()) : 
        while(have_posts()) : the_post(); 

            the_content();

        endwhile; ?>
    <?php endif; ?>
</div><!-- Container -->

<?php get_footer(); ?>