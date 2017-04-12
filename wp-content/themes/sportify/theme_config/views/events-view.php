    <div id="events_slider" class="events-slider">
        <ul class="clean-list">
            <?php foreach($slides as $i => $slide): ?>
                <?php $p_id = $slide ["post"] -> ID; ?>
                <li>
                    <div class="row">

                        <div class="col-md-4 col-sm-4 col-xs-4 no-padding">
                            <?php if ( !empty($slide['options']['photo']) ): ?>
                                <figure class="">
                                    <a href="#">
                                        <img src="<?php echo $slide['options']['photo'] ?>" alt="<?php echo get_the_title($slide['post']->ID); ?>" class="attachment-post-thumbnail wp-post-image" />
                                    </a>
                                </figure>
                            <?php endif; ?>
                            
                            <time datetime="<?php echo get_the_time('Y-m-d', $p_id); ?>" class="red-background text-center"><strong><?php echo get_the_time('d.m', $p_id); ?></strong></time>
                        </div>
                        <div class="col-md-8 col-sm-8 col-xs-8 no-padding">
                            <div class="event-desc">
                                <h3 class="entry-header white-background"><a href="#"><?php echo get_the_title($slide['post']->ID); ?></a></h3>
                                <div class="content">
                                    <?php echo $slide['post']->post_content ? apply_filters( 'the_content' , $slide['post']->post_content) : apply_filters( 'the_excerpt' , $slide['post']->post_excerpt) ?>
                                    <a href="<?php echo $slide['options']['more'] ?>" class="inline read-detailed"><?php _e('Read More', 'sportify'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>