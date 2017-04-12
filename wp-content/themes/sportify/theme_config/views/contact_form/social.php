<ul class="all-socials">
    <?php $social_platforms = array(
        'facebook',
        'twitter',
        'linkedin',
        'youtube',
        'pitenrest',
        'googleplus',
        'dribbble',
        'vimeo',
        'rss');
        foreach($social_platforms as $platform): 
            if (_go('social_platforms_' . $platform)):?>
                <li>
                    <a href="<?php echo _go('social_platforms_' . $platform) ?>"><i class="socials-<?php echo $platform ?>" title="<?php echo $platform ?>"></i></a>
                </li>
            <?php endif;
        endforeach;?>    
</ul>