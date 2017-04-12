    <div class="container">
        <ul class="clean-list grid friends-items friends-list text-center row">
            <?php foreach($slides as $i => $slide): ?>
                <?php if ( !empty($slide['options']['logo']) ): ?>
                <li class="col-md-3 col-sm-3 col-xs-6">
                    <figure class="invert">
                        <a class="rulmenpol-logo logo" href="<?php echo $slide['options']['url'] ?>" target="_blank">
                            <img src="<?php echo $slide['options']['logo'] ?>" alt="<?php echo get_the_title($slide['post']->ID); ?>" />
                        </a>
                    </figure>
                </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
