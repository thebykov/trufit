<?php  if ( $shortcode['content'] == 'true' ): ?>
 <section class="box classes-box" id="classes_box"><!-- Section Events -->
            <header class="text-center classes-box-header box-header">
                <div class="container">
                    <div class="white-border box-header-title-block aligncenter nowrap">
                        <h2 class="entry-header"><?php echo $shortcode['title']; ?></h2>
                    </div>
                    <h3 class="entry-description"><?php echo $shortcode['description']; ?></h3>
                </div>
            </header>

            <div class="container light-grey-background">
                <div class="row">
                    
                    <div class="col-md-6 no-padding">
                        <div class="boxing-classes" style="background-image: url('<?php echo $shortcode['image'] ?>')">
                            <div class="classes">
                                <nav class="classes-nav">
                                    <ul class="clean-list toggle-list clearfix">

                                        <?php foreach($slides as $i => $slide): ?>
                                            
                                            <li class="classes-menu-item ">
                                                <input type="radio" id="toggle-<?php echo $slide['post']->ID; ?>" name="toggle-helper" autocomplete="off">
                                                <label for="toggle-<?php echo $slide['post']->ID; ?>"><?php echo get_the_title( $slide['post']->ID ) ?></label>

                                                
                                                <div class="menu-item-content">
                                                    <div class="toggle-content white-background">
                                                        <header>
                                                            <h3 class="entry-header padding"><?php echo get_the_title($slide['post']->ID); ?>:</h3>
                                                        </header><hr />

                                                        <div class="content">
                                                                <?php echo apply_filters('the_excerpt', $slide['post']->post_excerpt); ?>
                                                        </div>
                                                        <?php  if ( !empty( $slide['options']['button'] )): ?>
                                                            <div class="white-background padding">
                                                                <a class="read-more light-green-hover text-center" href="<?php echo $slide['options']['button']['link'] ? $slide['options']['button']['link'] : '#'; ?>"><?php echo  $slide['options']['button']['link_text'] ? $slide['options']['button']['link_text'] : 'View Timeline' ; ?></a>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </nav><!-- /.main-nav -->
                            </div>
                        </div>


                    </div>
                    <div class="col-md-6 no-padding ">

                        <?php foreach($slides as $i => $slide): ?>
                            <div class="classes-content-block" id="classes_content_<?php echo $slide['post']->ID ?>" style="display:none;">
                                <header class="padding white-background">
                                    <h2 class="entry-header black-background"><?php echo get_the_title( $slide['post']->ID ) ?></h2>
                                </header>
                                
                                <div class="entry-content padding">
                                    <?php echo apply_filters('the_content', $slide['post']->post_content); ?>

                                     <?php  if ( !empty( $slide['options']['button'] )): ?>
                                        <div class="white-background">
                                            <a class="read-more text-center red-black-hover" href="<?php echo $slide['options']['button']['link'] ? $slide['options']['button']['link'] : '#'; ?>"><?php echo  $slide['options']['button']['link_text'] ? $slide['options']['button']['link_text'] : 'View Timeline' ; ?></a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
</section>
<?php else: ?>
    <div class="boxing-classes" style="background-image: url('<?php echo $shortcode['image'] ?>')">
        <div class="classes">
            <nav class="classes-nav">
                <ul class="clean-list toggle-list clearfix">

                    <?php foreach($slides as $i => $slide): ?>
                        
                        <li class="classes-menu-item ">
                            

                            <input type="radio" id="toggle-<?php echo $slide['post']->ID; ?>" name="toggle-helper" autocomplete="off" >
                            <label for="toggle-<?php echo $slide['post']->ID; ?>"><?php echo get_the_title( $slide['post']->ID ) ?></label>

                            
                            <div class="menu-item-content">
                                <div class="toggle-content white-background">
                                    <header>
                                        <h3 class="entry-header padding"><?php echo get_the_title($slide['post']->ID); ?>:</h3>
                                    </header><hr />

                                    <div class="content">
                                            <?php echo apply_filters('the_excerpt', $slide['post']->post_excerpt); ?>
                                    </div>
                                    <?php  if ( !empty( $slide['options']['button'] )): ?>
                                        <div class="white-background padding">
                                            <a class="read-more light-green-hover text-center" href="<?php echo $slide['options']['button']['link'] ? $slide['options']['button']['link'] : '#'; ?>"><?php echo  $slide['options']['button']['link_text'] ? $slide['options']['button']['link_text'] : 'View Timeline' ; ?></a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav><!-- /.main-nav -->
        </div>
    </div>
<?php endif; ?>
