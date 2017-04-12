<?php if( !empty($slides) ): ?>
    <section id="shop_box" class="box shop-box">
        <div class="light-grey-background trainers-background shop-block box-header" style="background-image: url('<?php echo $shortcode['image']; ?>')">
            <div class="container">
                <ul class="clean-list shop-items row">
                    <?php foreach($slides as $i => $slide): ?>
                        <li class="col-md-3 col-sm-3 col-xs-4 no-padding">
                            <div>
                                <?php if ( has_post_thumbnail( $slide['post']->ID ) ): ?>
                                    <figure>
                                        <a class="" href="#">
                                            <?php echo get_the_post_thumbnail( $slide['post']->ID ); ?>
                                        </a>
                                    </figure>        
                                <?php endif; ?>

                                
                                <?php foreach ($slide['options']['button'] as $key => $value): ?>
                                    <?php if ( $value ): ?>
                                        <div class="button grey-button"><a href="<?php echo $slide['options']['button']['link'] ? $slide['options']['button']['link'] : '' ?>"><?php echo $slide['options']['button']['text']; ?></a></div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                
                            </div>
                            
                            <div class="content-block red-background padding">
                                <h4 class="entry-header pre-line"><?php echo preg_replace( '/[\s\s+]/', '<br />', get_the_title($slide['post']->ID)); ?></h4>
                            </div>
                        </li>
                    <?php endforeach; ?>    
                </ul>
            </div>
        </div>
    </section>
<?php endif; ?>