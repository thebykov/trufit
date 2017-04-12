<?php

/*

    Template Name: Team

*/

?>

<?php get_header(); ?>

<div class="main-content content">

	<?php if (have_posts()) : 

        while(have_posts()) : the_post(); ?>

            <?php the_content(); ?>
            
            

        <?php endwhile; ?>

    <?php endif; ?>

</div>

<?php get_footer(); ?>

