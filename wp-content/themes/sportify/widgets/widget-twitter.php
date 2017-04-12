<?php
class Tesla_twitter_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
                'tesla_twitter_widget',
                '['.THEME_PRETTY_NAME.'] Twitter',
                array(
            'description' => __('A list of latest tweets. Please configure twitter using under THEME menu => Subscription Tab.', 'sportify'),
            'classname' => 'tesla_twitter_widget',
                )
        );
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $user = empty($instance['user']) ? 'teslathemes' : $instance['user'];
        if (empty($instance['number']) || !$number = absint($instance['number']))
            $number = 3;

        echo $before_widget;
        if (!empty($title))
            echo $before_title . $title . $after_title;

        echo twitter_generate_output($user, $number);

        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['user'] = strip_tags($new_instance['user']);
        $instance['number'] = (int)strip_tags($new_instance['number']);

        return $instance;
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => ''));
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $user = isset($instance['user']) ? esc_attr($instance['user']) : 'TeslaThemes';
        $number = isset($instance['number']) ? absint($instance['number']) : 3;
        ?>
        <p>
            <label><?php _ex('Title:','dashboard','sportify'); ?><input class="widefat" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label> 
            <label><?php _ex('Twitter user:','dashboard','sportify'); ?><input class="widefat" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo esc_attr($user); ?>" /></label> 
            <label for="<?php echo $this->get_field_id('number'); ?>">
                <?php _ex('Number of posts to show:','dashboard','sportify'); ?>
                <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
            </label>
        </p>
        <?php
    }
}

add_action('widgets_init', create_function('', 'return register_widget("Tesla_twitter_widget");'));