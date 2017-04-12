<?php

return array(
        'favico' => array(
                'dir' => '/theme_config/icons/portfolio.png'
        ),
        'option_saved_text' => 'Options successfully saved',
        'tabs' => array(
                array(
                        'title'=>'General Options',
                        'icon'=>1,
                        'boxes' => array(
                                'Logo Customization' => array(
                                        'icon'=>'customization',
                                        'size'=>'2_3',
                                        'columns'=>true,
                                        'description'=>'Here you upload a image as logo or you can write it as text and select the logo color, size, font.',
                                        'input_fields' => array(
                                                'Logo As Image'=>array(
                                                        'size'=>'half',
                                                        'id'=>'logo_image',
                                                        'type'=>'image_upload',
                                                        'note'=>'Here you can insert your link to a image logo or upload a new logo image.'
                                                ),
                                                'Logo As Text'=>array(
                                                        'size'=>'half_last',
                                                        'id'=>'logo_text',
                                                        'type'=>'text',
                                                        'note' => "Type the logo text here, then select a color, set a size and font",
                                                        'color_changer'=>true,
                                                        'font_changer'=>true,
                                                        'font_size_changer'=>array(8,50, 'px'),
                                                        'font_preview'=>array(true, true)
                                                )
                                        )
                                ),
                                'Favicon' => array(
                                        'icon'=>'customization',
                                        'size'=>'1_3_last',
                                        'input_fields' => array(
                                                array(
                                                        'id'=>'favicon',
                                                        'type'=>'image_upload',
                                                        'note'=>'Here you can upload the favicon icon.'
                                                )
                                        )
                                ),
                                'Custom CSS' => array(
                                        'icon'=>'css',
                                        'size'=>'half',
                                        'description'=>'Here you can write your personal CSS for customizing the classes you want. Or use our <b>Custom Styler</b>, from the Site Colors tab, for an easier custom css color picking.',
                                        'input_fields' => array(
                                                array(
                                                        'id'=>'custom_css',
                                                        'type'=>'textarea'
                                                )
                                        )
                                ),
                                'Custom JS' => array(
                                        'icon'=>'js',
                                        'size'=>'half_last',
                                        'description'=>'Here you can write your personal JS that will be appended to footer.<br><br>',
                                        'input_fields' => array(
                                                array(
                                                        'id'=>'custom_js',
                                                        'type'=>'textarea'
                                                )
                                        )
                                )
                        )
                ),
                array(
                        'title' => 'Colors Background',
                        'icon'=>4,
                        'boxes' => array(
                                'Background Customization'=>array(
                                        'icon'=>'customization',
                                        'columns'=>true,
                                        'size' => '1',
                                        'input_fields' => array(
                                                'Background Color'=>array(
                                                        'size'=>'half',
                                                        'id'=>'bg_color',
                                                        'type'=>'colorpicker'
                                                ),
                                                'Background Image' => array(
                                                        'size'=>'half_last',
                                                        'id'=>'bg_image',
                                                        'type'=>'image_upload'
                                                )
                                        )
                                ),
                                'Site Colors' => array(
                                        'icon'=>'background',
                                        'columns'=>true,
                                        'size' => '1',
                                        'input_fields' => array(
                                                'Primary Site Color'=>array(
                                                        'size'=>'half',
                                                        'id'=>'site_color',
                                                        'type'=>'colorpicker',
                                                        'note'=>'Choose primary color for your website. This will affect only specific elements.<br>To return to default color , open colorpicker and click the Clear button.'
                                                ),
                                                'Secondary Site Color'=>array(
                                                        'size'=>'half',
                                                        'id'=>'site_color_2',
                                                        'type'=>'colorpicker',
                                                        'note'=>'Choose secondary color for your website. This will affect only specific elements.<br>To return to default color , open colorpicker and click the Clear button.'
                                                ),
                                        )
                                )
                        )
                ),
                array(
                        'title' => 'SEO and Socials',
                        'icon'=>2,
                        'boxes'=>array(
                                'ShareThis feature'=>array(
                                        'icon'=>'social',
                                        'description'=>"To use this service please select your favorite social networks",
                                        'size'=>'3',
                                        'input_fields'=>array(
                                                array(
                                                        'type'  => 'select',
                                                        'id'    => 'share_this',
                                                        'label' => 'Facebbok',
                                                        'class'  => 'social_search',
                                                        'multiple' => true,
                                                        'options'=>array('Google'=>'googleplus','Facebook'=>'facebook','Twitter'=>'twitter','Pinterest'=>'pinterest',"Linkedin"=>'linkedin',"YouTube"=>'youtube')
                                                )
                                        )
                                ),
                                'Social Platforms'=>array(
                                        'icon'=>'social',
                                        'description'=>"Insert the link to the social share page.",
                                        'size'=>'3',
                                        'columns'=>true,
                                        'input_fields'=>array(
                                                array(
                                                        'id'=>'social_platforms',
                                                        'size'=>'half',
                                                        'type'=>'social_platforms',
                                                        'platforms'=>array('facebook','twitter', 'pinterest', 'dribbble', 'youtube', 'instagram', 'googleplus', 'linkedin')
                                                )
                                        )
                                ),
                                'Tracking Code' => array(
                                        'icon'=>'track',
                                        'size'=>'3_last',
                                        'input_fields'=>array(
                                                array(
                                                        'type'=>'textarea',
                                                        'id'=>'tracking_code'
                                                )
                                        )
                                )
                        )
                ),
                array(
                        'title' => 'Additional Options',
                        'icon'  => 6,
                        'boxes' => array(
                                '404 page settings'=>array(
                                        'icon' => 'customization',
                                        'description'=>"Setup your 404 page",
                                        'size'=>'2_3',
                                        'input_fields' =>array(
                                                'Page title' => array(
                                                        'id'    => 'error_title',
                                                        'type'  => 'text',
                                                        'note' => 'This is the title of the 404 page',
                                                ),
                                                'Message' => array(
                                                        'id'    => 'error_message',
                                                        'type'  => 'textarea',
                                                        'note' => 'This message will appear on 404 page. Wrap text in [<span></span>] to enhance it .',
                                                )
                                        )
                                ),
                                'Twitter Settings'=>array(
                                        'icon' => 'customization',
                                        'description'=>"Used by the Twitter widget. Visit <a href='https://dev.twitter.com/apps/new' target='_blank'>Twitter Apps</a> , create your App , press 'Generate Access token at the bottom', insert the following from the 'Oauth' tab.",
                                        'size'=>'1_3_last',
                                        'columns'=>false,
                                        'input_fields' =>array(
                                                'Consumer Key' => array(
                                                        'id'    => 'twitter_consumerkey',
                                                        'type'  => 'text',
                                                        'size' => '1'
                                                ),
                                                'Consumer Secret' => array(
                                                        'id'    => 'twitter_consumersecret',
                                                        'type'  => 'text',
                                                        'size' => '1',
                                                ),
                                                'Access Token' => array(
                                                        'id'    => 'twitter_accesstoken',
                                                        'type'  => 'text',
                                                        'size' => '1',
                                                ),
                                                'Access Toekn Secret' => array(
                                                        'id'    => 'twitter_accesstokensecret',
                                                        'type'  => 'text',
                                                        'size' => '1',
                                                )
                                        )
                                ),
                                'Page Settings'=>array(
                                        'icon' => 'customization',
                                        'description'=>"Other settings",
                                        'size'=>'1',
                                        'columns'=>false,
                                        'input_fields' =>array(
                                                'Main Header Banner' => array(
                                                        'id'    => 'ad_image',
                                                        'size' => 'half',
                                                        'note' => 'Banner 730x64px',
                                                        'type'  => 'image_upload'
                                                ),
                                                'Main Header Banner Link' => array(
                                                        'id'    => 'ad_url',
                                                        'size' => 'half_last',
                                                        'note' => 'Add Link for Main Header Banner ',
                                                        'type'  => 'text'
                                                ),
                                                'Single team member header' => array(
                                                        'id'    => 'single_team_header',
                                                        'size' => 'half',
                                                        'note' => 'Add content to single team header. (Shortcodes possible).',
                                                        'type'  => 'textarea'
                                                )
                                        )
                                ),
                                'Copyright Message'=>array(
                                        'icon' => 'customization',
                                        'description'=>"Other settings",
                                        'size'=>'1',
                                        'columns'=>false,
                                        'input_fields' =>array(
                                                'Copyright' => array(
                                                        'id'    => 'copyright_message',
                                                        'type'  => 'text',
                                                        'note' => 'Message that will appear in the footer.',
                                                )
                                                
                                        )
                                )
                        )
                ),
                array(
                        'title' => 'Contact Info',
                        'icon'  => 5,
                        'boxes' => array(
                                'Contact info'=>array(
                                        'icon' => 'customization',
                                        'description'=>"Provide contact information. This information will appear in contact template. For more informations read documentation.",
                                        'size'=>'2_3',
                                        'columns'=>true,
                                        'input_fields' =>array(
                                                'Map' => array(
                                                        'id'    => 'contact_map',
                                                        'type'  => 'map',
                                                        'note' => 'Here you can insert iframe with your location. For more information you can find in theme\'s documentation' ,
                                                        'size' => 'half',
                                                        'icons' => array('image.png')
                                                ),
                                                'Contact form' => array(
                                                        'id'    => 'contact_form',
                                                        'type'  => 'checkbox',
                                                        'label' => 'To use Contact Form , this checkbox must be checked',
                                                        'size' => 'half_last',
                                                        'action' => array('show',array('title_contact'))
                                                ),
                                                array(
                                                        'id'    => 'title_contact',
                                                        'type'  => 'text',
                                                        'note' => 'Contact form header',
                                                        'size' => 'half',
                                                        'placeholder' => 'Drop us a line'
                                                ),
                                                array(
                                                        'id'    => 'email_contact',
                                                        'type'  => 'text',
                                                        'note' => 'Provide an email, used to recive messages from Contact Form',
                                                        'size' => 'half_last',
                                                        'placeholder' => 'Contact Form Email'
                                                ),
                                                array(
                                                        'id'    => 'title_address',
                                                        'type'  => 'text',
                                                        'note' => 'Address header',
                                                        'size' => 'half',
                                                        'placeholder' => 'Contact Info'
                                                ),
                                                'Contact address' => array(
                                                        'id'    => 'content_address',
                                                        'type'  => 'textarea',
                                                        'note' => 'Write a message to appear beffore in the address box.',
                                                        'size' => 'half_last',
                                                        'placeholder' => 'Contact our office in NYC'
                                                ),
                                                array(
                                                        'id'    => 'contact_address',
                                                        'type'  => 'textarea',
                                                        'note' => 'Provide your address',
                                                        'placeholder' => 'Address',
                                                        'size' => 'half'
                                                ),
                                                array(
                                                        'id'    => 'contact_phone',
                                                        'type'  => 'text',
                                                        'note' => 'Provide your phone number',
                                                        'size' => 'half',
                                                        'placeholder' => 'Phone number'
                                                ),
                                                array(
                                                        'id'    => 'contact_fax',
                                                        'type'  => 'text',
                                                        'note' => 'Provide your fax number',
                                                        'size' => 'half',
                                                        'placeholder' => 'Fax number'
                                                ),
                                                'Footer banner image'=>array(
                                                        'size'=>'half',
                                                        'id'=>'footer_banner',
                                                        'type'=>'image_upload',
                                                        'note'=>'Here you can insert your link to the footer banner image.'
                                                ),
                                                
                                        )
                                )

                        )
                ),
                array(
                        'title' => 'Typography',
                        'icon'  => 1,
                        'boxes' => array(
                                'Font Changers'=>array(
                                        'icon' => 'customization',
                                        'description'=>'Change the fonts & colors for site\'s sections:',
                                        'size'=>'1',
                                        'columns'=>true,
                                        'input_fields' => array(
                                                'Main Content Font/Color'=>array(
                                                    'size'=>'1_3',
                                                    'id'=>'main_content_text',
                                                    'type'=>'text',
                                                    'note' => "Then select a color, set a size and choose a font",
                                                    'color_changer'=>true,
                                                    'font_changer'=>true,
                                                    'font_size_changer'=>array(8,50, 'px'),
                                                    'hide_input'=>true,
                                                    ),
                                                'Sidebar Font/Color'=>array(
                                                    'size'=>'1_3',
                                                    'id'=>'sidebar_text',
                                                    'type'=>'text',
                                                    'note' => "Then select a color, set a size and choose a font",
                                                    'color_changer'=>true,
                                                    'font_changer'=>true,
                                                    'font_size_changer'=>array(8,50, 'px'),
                                                    'hide_input'=>true,
                                                    ),
                                                'Menu Font/Color'=>array(
                                                    'size'=>'1_3_last',
                                                    'id'=>'menu_text',
                                                    'type'=>'text',
                                                    'note' => "Then select a color, set a size and choose a font",
                                                    'color_changer'=>true,
                                                    'font_changer'=>true,
                                                    'font_size_changer'=>array(8,50, 'px'),
                                                    'hide_input'=>true,
                                                    ),
                                                
                                        )
                                )
                        )
                ),
                array(
                        'title' => 'Our Themes',
                        'icon'  => 8,
                        'type'=>'iframe',
                        'link'=>'http://teslathemes.com/our-themes/'
                )

        ),
        'styles' => array( array('wp-color-picker'),'style','select2' )
        ,
        'scripts' => array( array( 'jquery', 'jquery-ui-core','jquery-ui-datepicker','wp-color-picker' ), 'select2.min','jquery.cookie','tt_options', 'admin_js' )
);