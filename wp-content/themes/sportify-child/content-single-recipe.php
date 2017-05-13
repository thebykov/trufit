<?php if((current_user_can('member') || current_user_can('special_member') || current_user_can('owner') || current_user_can('administrator'))): ?>           
<div class="post grey-background no-margin">
    <header>
        <?php if( has_post_thumbnail() ): ?>
            <figure>
                <?php the_post_thumbnail(); ?>
            </figure>
        <?php endif; ?>
        
        <ul class="inline-list post-meta-list row">
            <li class="col-md-4 col-xs-5 no-padding"><a class="text-red hover-text-dark-red blog-post-date date"><?php the_time('d M') ?></a></li>
            <li class="col-md-6 col-xs-5 no-padding"><a href="#" class="text-red hover-text-dark-red author"><?php the_author_link(); ?></a></li>
            <li class="col-md-2 col-xs-2 no-padding">
                <a class="meta-link comments" href="<?php the_permalink(); ?>"><?php comments_number( '0', '1', '%' ) ?></a>
            </li>
        </ul>
        <h2 class="entry-header text-center"><?php the_title(); ?></h2><div class="tags-list post-tags-list"><?php the_terms( $post->ID, 'recipe_tags', '', ', ', ' ' ); ?></div><hr />
    </header>

    <div class="entry-content margin padding white-background">
        <?php the_content()?>
        <div class="social-links padding-top clearfix">
            <?php if ( _go('share_this') ){ tt_share(); } ?>
        </div>
    </div>
</div>

<div class="comments-wrap">  
    <?php comments_template( ); ?>
</div>

   
<div class="entry-footer">
    <div class="post_pagination">
        <?php wp_link_pages(array(
            'before'           => '<ul class="page-numbers center"><li>',
            'after'            => '</li></ul>',
            'link_before'      => '',
            'link_after'       => '',
            'next_or_number'   => 'number',
            'separator'        => '</li><li>',
            'nextpagelink'     => __( 'Next page','sportify' ),
            'previouspagelink' => __( 'Previous page','sportify' ),
            'pagelink'         => '%',
            'echo'             => 1
        )); ?>
    </div>
</div>
<?php else: ?>
<br><br>
<h2 style="color: #fff; text-align:center">Special Access is required to see this content. <br>Please contact us if you believe this is a mistake.</h2>
<br><br>
<?php endif; ?>