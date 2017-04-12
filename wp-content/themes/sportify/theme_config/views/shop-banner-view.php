<section id="shop_banner_box" class="box shop-banner-box margin-top">
    <div class="container">
        <div class="top-selling-block">
            <div class="row no-margin">
                <?php foreach($slides as $i => $slide): 
                        $image = $slide['options']['image'] ? $slide['options']['image'] : '';
                        $counter = ($i == 0) ? 1 : ++$counter;
                        if ( $counter%2 == 0 && $i > 0): ?>
                            <div class="col-md-5 col-sm-5 no-padding">
                        <?php else: ?>
                            <div class="col-md-7 col-sm-7 no-padding">
                        <?php endif; ?>
                            <header style="background-image: url(<?php echo $image ?>);">
                                <?php  $phrases = explode( ' ', trim( get_the_title($slide['post']->ID) ) ); ?>
                                    <h2 class="entry-header"><a href="<?php echo $slide['options']['link'] ?>"><?php foreach ( $phrases as $phrase ){ echo $phrase.'<br />'; } ?></a></h2>
                            </header>
                            </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>