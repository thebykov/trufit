<?php
/*
    Template Name: Shop
*/
?>
<?php /* get_header('shop');*/ get_header(); ?>

<div class="main-content shop-content content">
    <?php //Slider
    $slider_category = get_post_meta($post->ID,THEME_NAME . '_slider_categ',true);
    if ( $slider_category && class_exists('RevSliderFront') ) {
        $rvslider = new RevSlider();
        $arrSliders = $rvslider->getArrSliders();
        if( !empty( $arrSliders ) ) {
            foreach ($arrSliders as $revSlider) {
                if($revSlider->getAlias() === $slider_category)
                    $revSliderAlias = $revSlider->getAlias();
            }
        }
    }
    if(!empty($revSliderAlias)):
        putRevSlider( $revSliderAlias );
    endif;?>
    <?php if (have_posts()) : 
        while(have_posts()) : the_post(); ?>
            <?php the_content(); ?>
        <?php endwhile; ?>
    <?php endif; ?>
</div>


<?php get_footer(); ?>