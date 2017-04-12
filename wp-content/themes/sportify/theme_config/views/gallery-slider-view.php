    <div class="gallery-slider-wrap">
        <div id="gallery_slider" class=" gallery-slider ovh">
            <ul id="gallery_list" class="gallery-list clean-list">
                <?php $counter = 0; $max_count = 4; $is_quarter = 0; ?>
                <?php foreach($slides as $i => $slide): ?>
                    <?php 
                        $is_quarter = $slide['options']['size'] == 'quarter' ? ++$counter : $counter = 0;

                            if ( $is_quarter == 1 || !$counter ): // start the list ?> 
                                <li class="ovh">
                                    <ul class="clean-list row no-spacing <?php echo $is_quarter ? 'quarter-size' : ''  ?>">
                        <?php endif; ?> 
                                
                                <li class="<?php echo $counter ? 'col-md-6 col-sm-6 col-xs-6' : 'col-md-12' ?>">
                                    <div class="relative">
                                        <?php if ( !empty( $slide['options']['photo'] ) ): ?>
                                            <figure class="shape-square">
                                                <a href="">
                                                    <img src="<?php echo $slide['options']['photo'] ?>" alt="<?php echo get_the_title($slide['post']->ID); ?>" />
                                                </a>
                                            </figure>
                                        <?php endif; ?>
                                        <div class="gallery-photo-desc text-center">
                                            <div>
                                                <a href=""><?php echo get_the_title($slide['post']->ID); ?></a>
                                                <a href="<?php echo $slide['options']['photo'] ?>" class="zoom-image inline margin-top"></a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                        
                        <?php if( !$counter || ( $is_quarter === $max_count )  ): ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                    
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>