<section id="page_slider_box" class="box page-slider-box box-header"><!-- Section Slider -->
    <div class="container no-padding relative"> 
        <div class="page-slider-block light-grey-background" id="page_slider">
            <ul class="clean-list">
                <?php foreach($slides as $i => $slide): ?>
                    <li class="row no-margin">
                        <div class="col-md-8 col-sm-6 col-xs-6">
                            <div class="padding margin">
                                <?php if ( !empty( $slide['options']['title'] ) ): ?>
                                    <h2 class="entry-header"><?php echo $slide['options']['title'] ?></h2>
                                <?php endif; ?>
                                    <h3 class="entry-subheader"><?php echo get_the_title($slide['post']->ID); ?></h3>
                                <div class="slide-content ovh">
                                    <?php echo '<p>'.$slide['post']->post_content.'</p>' ?>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-6 no-padding">
                            <?php if ( !empty( $slide['options']['photo'] ) ): ?>
                                <figure>
                                       <img src="<?php echo $slide['options']['photo'] ?>" alt="<?php echo $slide['options']['title'] ?>" />
                                </figure>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>