    <div class="container">
        <ul class="clean-list clients-items clients-list">
            <?php foreach($slides as $i => $slide): ?>
                <li class="ovh row">
                    <?php if ( $i % 2 == 0 ): ?>
                        <?php if ( !empty( $slide['options']['photo'] ) ): ?>
                            <figure class="col-md-4 no-padding">
                                <a href="?page=team-single" class="">
                                    <img src="<?php echo $slide['options']['photo'] ?>" alt="<?php echo get_the_title($slide['post']->ID); ?>">
                                </a>
                            </figure>
                        <?php endif; ?>
                        
                        <div class="client-block padding ovh col-md-8">
                            <header>
                                <h3 class="entry-header text-center">
                                     <?php echo get_the_title($slide['post']->ID); ?>
                                </h3>
                            </header>
                            <div class="client-content padding-top">
                                <?php echo apply_filters('the_content', $slide['post']->post_content); ?>
                            </div>
                        </div>

                    <?php else: ?>
                        <div class="client-block padding ovh col-md-8">
                            <header>
                                <h3 class="entry-header text-center">
                                     <?php echo get_the_title($slide['post']->ID); ?>
                                </h3>
                            </header>
                            <div class="client-content padding-top">
                                <?php echo apply_filters('the_content', $slide['post']->post_content); ?>
                            </div>
                        </div>
                            
                        <?php if ( !empty( $slide['options']['photo'] ) ): ?>
                            <figure class="col-md-4 no-padding">
                                <a href="?page=team-single" class="">
                                    <img src="<?php echo $slide['options']['photo'] ?>" alt="<?php echo get_the_title($slide['post']->ID); ?>">
                                </a>
                            </figure>
                        <?php endif; ?>
                    <?php endif; ?>
                    

                    
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>