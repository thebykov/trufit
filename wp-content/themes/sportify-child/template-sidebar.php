<?php
/*
    Template Name: Page with Sidebar
*/
get_header(); ?>
<div class="container">
    <div class="white-background margin-top-40 padding page-minheight ovh page-content content">
        <header>
            <h2><?php the_title(); ?></h2>
             <?php if( has_post_thumbnail() ): ?>
                <figure>
                    <?php the_post_thumbnail(); ?>
                </figure>
            <?php endif; ?>
        </header>
        <div class="row">
	        <div class="col-md-8">
			    <?php if (have_posts()): 
			        while(have_posts()): the_post(); 
			            the_content();
			        endwhile; ?>
			    <?php endif; ?>
		    	<div class="comments-wrap"> 
	        		<?php comments_template( ); ?>
	        	</div>
			</div>
			<div class="col-md-4">
				<?php get_sidebar("page"); ?>
	    	</div>
    	</div>
    </div>
</div><!-- Container -->
<?php get_footer(); ?>