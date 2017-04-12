    <div class="container">
        <ul class="clean-list services-list featured-items row">
            <?php foreach($slides as $i => $slide): ?>
                <li class="col-md-4 col-sm-4">
                    <div class="hover-scale-100">
                        
                        <figure>
                            <?php if ( $slide['options']['photo'] ): ?>
                                <a class="font-4x grey text-dark-blue hover-blue" href="#">
                                    <img alt="<?php echo get_the_title( $slide['post']->ID ); ?>" src="<?php echo $slide['options']['photo']; ?>">
                                </a>
                            <?php endif; ?>

                           
                            <?php if ( $slide['options']['icon'] ): ?>
                                <figcaption class="transition-medium">
                                    <a href="<?php echo !empty($slide['options']['link']) ? $slide['options']['link'] : "#" ; ?>" class="shape-round">
                                        <img src="<?php echo $slide['options']['icon'] ?>" alt="<?php echo get_the_title( $slide['post']->ID ); ?>" />
                                    </a>
                                </figcaption>
                            <?php endif; ?>
                        </figure>
                        
                        <div class="content-block white-background">
                            <h4 class="entry-title pre-line padding"><?php echo get_the_title( $slide['post']->ID ); ?></h4><hr />
                            <div class="content padding">
                                <?php echo apply_filters( 'the_content' , $slide['post']->post_content);?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>