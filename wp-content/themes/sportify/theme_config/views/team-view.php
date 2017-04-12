    <ul class="clean-list team-items team-list row">
        <?php foreach($slides as $i => $slide): ?>
            <li class="col-md-2 col-sm-4 col-xs-6">
                <div class="team-item hover-scale-120 transition-medium">
                    <header>
                        <h4 class="">
                            <a href="<?php echo get_permalink($slide['post']->ID); ?>#member" class="text-dark-blue hover-text-blue wrapword transition-medium">
                                <?php
                                    echo preg_replace( '/[\s\s+]/', ' <br />', get_the_title($slide['post']->ID));
                                ?>
                            </a>
                        </h4>
                    </header>

                    <figure>
                        <a href="<?php echo get_permalink($slide['post']->ID); ?>#member">
                            <img src="<?php echo $slide['options']['photo'] ?>" alt="<?php echo get_the_title($slide['post']->ID); ?>" />
                        </a>
                    </figure>
                    <div class="white-background team-desc-block">
                              <?php if ( !empty( $slide['categories'] ) ): ?>
                                        <ul class=" text-center clean-list">
                                            <li class="cat-item">
                                                <?php foreach( $slide['categories'] as $slug => $category ): ?>
                                                    <a title="" href=""><?php echo $category ?></a>
                                                <?php  endforeach; ?>
                                            </li>
                                            
                                        </ul><hr />
                                    <?php endif; ?>
                        <div class="team-desc">
                            <p class="">
                                <?php echo $slide['post']->post_excerpt; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>