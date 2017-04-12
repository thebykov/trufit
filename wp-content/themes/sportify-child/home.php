<?php get_header(); ?>
	<div class="main-content content">
        <div  id="blog_box" class="box blog-box margin-top"><!-- Section Events -->
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                    	<?php if (have_posts()) : ?>
							<!--<div class="clean-list blog-items row no-margin">-->
							    <?php while(have_posts()) : the_post();

							    	get_template_part('content','blog');
							    endwhile; ?>
					    	<!--</div>-->

		    	            <?php get_template_part('nav','main')?>
						<?php endif; ?>
                    </div>
                    <div class="col-md-4">
						<?php get_sidebar(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>