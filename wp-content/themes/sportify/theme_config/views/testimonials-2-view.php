</div>
<!-- ==================== START TESTIMONIALS === -->
<div class="testimonal-1" data-tesla-plugin="slider" data-tesla-item=".testimonial" data-tesla-next=".testimonial-right" data-tesla-prev=".testimonial-left" data-tesla-container=".testimonial-wrapper">
    <div class="container">
        <h2 class="center"><?php echo $shortcode['title'] ?></h2>
            <div class="testimonial-wrapper">
                <?php foreach($slides as $i => $slide): ?>
                    <div class="testimonial">
                        <?php echo apply_filters('the_content', $slide['post']->post_content); ?>
                        <span><?php echo get_the_title($slide['post']->ID); ?></span>
                        <div class="testimonial-left"></div>
                        <div class="testimonial-right"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<!-- ==================== END TESTIMONIALS === -->
<div class="container">