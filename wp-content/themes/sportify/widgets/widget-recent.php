<?php
 // POPULAR POST WIDGET
 class recent_news extends WP_Widget {
 
    function __construct() {
        parent::__construct(
                'recent_news',
                '['.THEME_PRETTY_NAME.'] Popular - Recent',
                array(
                    'description' => __('Shows your recent events.', 'sportify'),
                    'classname' => 'widget_recent_new',
                )
        );
    }

 
    function widget($args, $instance){
        extract($args);
        //$options = get_option('custom_recent');
        $title = $instance['title'];
        $number = $instance['posts'];

        //global $postcount;

        echo !empty($title) ? $before_widget.'<header class="accord-head active">'. $before_title .'<a href="#">' . $title  .'</a>'. $after_title .'
        </header>' : $before_widget; 
        ?>

        <ul id="toggle_tabs" class="tabs nav nav-tabs row">
            <li class="tab-popular col-md-6 col-sm-6 col-xs-6 no-padding active">
                <a data-toggle="tab" href="#tab_popular"><?php _e('Popular', 'sportify'); ?></a>
            </li>

            <li class="tab-new col-md-6 col-sm-6 col-xs-6 no-padding">
                <a data-toggle="tab" href="#tab_new"><?php _e('New', 'sportify'); ?></a>
            </li>
        </ul>
    <?php 
    $args = array(       
        //Type & Status Parameters
        'post_type'   => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $number,
        'ignore_sticky_posts' => 1,
        
        //Order & Orderby Parameters
        'order' => 'DESC',
        'orderby' => 'date',
    );
    $query = new WP_QUERY( $args ); ?>
    
    
<div class="tab-content white-background">

    <div class="tab-pane fade" id="tab_new">

        <?php if ( $query->have_posts() ) : ?>
            <ul>
            <?php while( $query->have_posts() ): $query->the_post() ?>
                <li>
                    <div class="row no-margin">
                        <div class="col-md-3 col-sm-4 col-xs-4 no-padding">
                            <?php if (has_post_thumbnail()): ?>
                                <figure class="">
                                    <a href="#"><?php the_post_thumbnail('thumbnail'); ?></a>
                                </figure>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-9 col-sm-8 col-xs-8 no-padding">
                            <div class="event-desc">
                                <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                <time class="red-background text-center" datetime="<?php the_time('Y-m-d'); ?>"><strong><?php the_time('d-m'); ?></strong></time>
                                <div class="content">
                                    <?php apply_filters('the_excerpt', get_the_excerpt()); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            <!-- a href="<?php the_permalink() ?>"><?php //echo substr(strip_tags(get_the_content()), 0, 80);  ?>...</a></p -->

            <?php endwhile; ?>
        </ul>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    </div>
    
    <?php 
        $args = array(       
            //Type & Status Parameters
            'post_type'   => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $number,
            'ignore_sticky_posts' => 1,
            
            //Order & Orderby Parameters
            'order' => 'DESC',
            'orderby' => 'comment_count',
        );

        $query = new WP_QUERY( $args ); ?>
    
    
        
    <div class="tab-pane fade in active" id="tab_popular">
        <?php if ( $query->have_posts() ): ?>
            <ul>
            <?php while( $query->have_posts() ): $query->the_post() ?>
                <li>
                    <div class="row no-margin">
                        <div class="col-md-3 col-sm-4 col-xs-4 no-padding">
                            <?php if (has_post_thumbnail()): ?>
                                <figure class="">
                                    <a href="#"><?php the_post_thumbnail('thumbnail'); ?></a>
                                </figure>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-9 col-sm-8 col-xs-8 no-padding">
                            <div class="event-desc">
                                <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                <time class="red-background text-center" datetime="<?php the_time('Y-m-d'); ?>"><strong><?php the_time('d-m'); ?></strong></time>
                                <div class="content">
                                    <?php apply_filters('the_excerpt', get_the_excerpt()); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
        <?php endwhile; ?>
        </ul>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    </div>

</div>



    
    <?php echo $after_widget;

    }
 
    function update($newInstance, $oldInstance){
        $instance = $oldInstance;
        $instance['title'] = strip_tags($newInstance['title']);
        $instance['posts'] = $newInstance['posts'];
        return $instance;
    }
 
    function form($instance){
        echo '<p ><label  for="'.$this->get_field_id('title').'">' . __('Title:', 'widgets') . '  <input style="width: 200px;" id="'.$this->get_field_id('title').'"  name="'.$this->get_field_name('title').'" type="text"  value="'.$instance['title'].'" /></label></p>';
        
        echo '<p ><label  for="'.$this->get_field_id('posts').'">' . __('Number of Posts:',  'widgets') . ' <input style="width: 50px;"  id="'.$this->get_field_id('posts').'"  name="'.$this->get_field_name('posts').'" type="text"  value="'.$instance['posts'].'" /></label></p>';

        echo '<input type="hidden" id="custom_recent" name="custom_recent" value="1" />';
    }
}
 
add_action('widgets_init', create_function('', 'return register_widget("recent_news");')); ?>