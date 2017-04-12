<?php get_header(); ?>
    <?php if (_go('single_team_header')) 
    	echo do_shortcode( _go('single_team_header') ); ?>
    <div class="content">        
		<?php if (have_posts()) : 
	        while(have_posts()) : the_post();
	        	echo Tesla_slider::get_slider_html('team','','single', $post->id);
			endwhile;
	    endif; ?>
    </div>

<?php get_footer(); ?>