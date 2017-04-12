<?php
/*
    Template Name: Timeline
*/
?>
<?php get_header(); ?>

<div class="main-content content">
    <div  id="timeline_box" class="box timeline-box"><!-- Section Events -->
        <div class="container">
        	<?php if (have_posts()) : 
		        while(have_posts()) : the_post(); ?>
		            <?php the_content(); ?>
		        <?php endwhile; ?>
		    <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>