<?php       
    if ( tesla_has_woocommerce() && ( is_woocommerce() || is_shop() || is_account_page() || is_checkout() || is_cart() || is_woocommerce() ) ){
        get_header('shop');
    }else {
        get_header();
    }
?>
<div class="container">
    <?php if ( !tesla_has_woocommerce() || ( !is_shop() && !is_account_page() && !is_checkout() && !is_cart() && !is_woocommerce() ) ): ?>
        <div class="white-background margin-top-40 padding page-minheight ovh page-content content">
            <header>
                <h2><?php the_title(); ?></h2>
                 <?php if( has_post_thumbnail() ): ?>
                    <figure>
                        <?php the_post_thumbnail(); ?>
                    </figure>
                <?php endif; ?>
            </header>

    <?php endif; ?>
    <?php if (have_posts()): 
        while(have_posts()): the_post(); 
            the_content();
        endwhile; ?>
    <?php endif; ?>

    <div class="comments-wrap">  
        <?php comments_template( ); ?>
    </div>
    
    <?php if ( !tesla_has_woocommerce() || ( !is_shop() && !is_account_page() && !is_checkout() && !is_cart() && !is_woocommerce() ) ): ?>
        </div>
    <?php endif; ?>
</div><!-- Container -->



<?php get_footer(); ?>