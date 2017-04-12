<?php
/*
    Template Name: Home
*/
?>

<?php get_header();
//Slider
$slider_category = get_post_meta($post->ID, THEME_NAME . '_slider_categ', true);

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

if( !empty( $revSliderAlias ) ):
    echo '<div id="slider_box" class="box slider-box">';
        putRevSlider( $revSliderAlias );
    echo '</div>';
endif; ?>

<div class="main-content content">
    <?php if (have_posts()): 
        while(have_posts()): the_post(); 
            the_content();
        endwhile; ?>
    <?php endif; ?>
</div>

<?php get_footer(); ?>