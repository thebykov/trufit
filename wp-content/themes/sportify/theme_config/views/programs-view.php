<div class="programms-block block-padding ovh">
    <header class="block-header ovh">
        <h2 class="entry-header">
            <a href="<?php echo !empty($shortcode['link']) ? $shortcode["link"] : "#" ?>"><?php echo $shortcode['title']; ?></a>
        </h2>
        <time class="light-green-background text-center" datetime="10-04"><strong><?php echo date_i18n("l"); ?></strong></time>
    </header>
    <ul class="row clean-list programm-list">

    <?php 
        $max = intval( $shortcode['max_number'] );
        $counter = 0;
        $counter2 = 0;

        if ( count($slides) ): ?>
            <?php foreach( $slides as $i => $slide):
                ++$counter; if ($counter > $max) break;

                if ( !empty($slide['options']['event']) ):

                    foreach ($slide['options']['event'] as $key => $option): ?>

                        <?php 
                            $start = $option['start_hour'].':'.$option['start_min'];
                            $end = $option['end_hour'].':'.$option['end_min'];
                            $dash = ' &ndash; ';
                        ?>
                        <?php if(date("l") == $option['days']): $counter2++;?>
                        <li>
                            <div class="col-md-3 col-sm-4 col-xs-4 programm-time"><?php echo intval($option['end_hour']) >= intval($option['start_hour']) ? $start.$dash.$end : $start.$dash.$start ?></div>

                            <div class="col-md-2 col-sm-3 col-xs-3 programm-class"><span><?php echo get_the_title($slide['post']->ID); ?></span></div>
                            <div class="col-md-7 col-sm-5 col-xs-5 programm-trainer"><?php echo !empty( $option['description'] ) ? $option['description'] : ''; ?></div>

                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; endif;?>
        <?php if($counter2 == 0): ?>
            <div class="col-md-7 col-sm-5 col-xs-5 programm-trainer">
                 <?php _e("There are no programs for today !","sportify"); ?>
            </div>
        <?php endif; ?>
    </ul>
</div>