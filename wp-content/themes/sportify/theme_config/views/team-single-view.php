<?php $slide = $slides[0]; ?>
<section  id="member_box" class="box member-box box-padding">
    <div class="container white-background">
        <div class="row">
            <div class="col-md-6 no-padding">
                <?php if( has_post_thumbnail() ): ?>
                    <figure>
                        <a href="" class="transition-short" id="member">
                            <?php the_post_thumbnail(); ?>
                        </a>
                    </figure>
                <?php endif; ?>
            </div>
            <div class="col-md-6 no-padding">
                
            <header class="black-background">
                    <?php if ( !empty( $slide['categories'] ) ): ?>
                            <p class="alignright">
                                <?php foreach( $slide['categories'] as $slug => $category ): ?>
                                    <a rel="category tag" title="" href=""><?php echo $category ?></a>
                                <?php  endforeach; ?>
                            </p>
                    <?php endif; ?>
                
                <h2 class="entry-header"><?php the_title(); ?></h2>
            </header>
                
                <div class="content light-grey-background">
                    <?php 
                        $content = get_the_content();
                        $content ? the_content() : the_excerpt();
                    ?>
                </div>

                <ul class="clean-list social-links clearfix">
                    
                    <?php 
                        if ( !empty( $slide['options']['social'] ) ){
                            foreach ($slide['options']['social'] as $key => $value) {
                                if ( !empty($value) ){
                                    echo '<li class="social-'.$key.'"><a href="'.$value.'" title="'.$key.'"><i class="icon-'.$key.'"></i></a></li>';
                                }
                            }
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</section>