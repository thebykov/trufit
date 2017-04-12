        <div id="home_slider" class="home-slider">    
            <ul class="clean-list">    
                <?php foreach($slides as $i => $slide): ?>
                    <li>
                        <header><h2 class="entry-header text-center"><?php echo get_the_title($slide['post']->ID); ?></h2></header>
                        <div class="classes-slider-content">
                            <?php if ( !empty( $slide['options']['video'] ) ): ?>
                                <?php echo $slide['options']['video'] ?>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>