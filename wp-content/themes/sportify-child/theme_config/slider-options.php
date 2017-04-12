<?php

return array(
	'services' => array(
		'name' => 'Services',
		'term' => 'service',
		'term_plural' => 'services',
		'order' => 'ASC',
		'has_single' => true,
		'post_options' => array('supports'=>array('title','editor')),
		'taxonomy_options' => array('show_ui'=>false),
		'options' => array(
			'photo' => array(
				'title' => 'Add Image for *Services*',
				'description' => 'Add Image for *Services*',
				'type' => 'image',
				'default' => 'holder.js/940x799/auto'
			),
			'icon' => array(
				'title' => 'Add Icon for *Services*',
				'description' => 'Add Icon for *Services*',
				'type' => 'image',
				'default' => 'holder.js/32x32/auto'
			)
		),
		'icon' => 'icons/portfolio.png',
		'output_default' => 'main',
		'output' => array(
			'main' => array(
				'shortcode' => 'tesla_services',
				'view' => 'views/services-view',
				'shortcode_defaults' => array(
					'title' => 'OUR SERVICES',
					'nr' => 0
				)
			)
		)
	),
	'classes' => array(
		'name' => 'Classes',
		'term' => 'classes',
		'term_plural' => 'classes',
		'order' => 'ASC',
		'has_single' => true,
		'post_options' => array('supports'=>array('title','editor', 'excerpt')),
		'taxonomy_options' => array('show_ui'=>false),
		'options' => array(
			'button' => array(
				'title' => 'Button',
				'description' => '',
				'type' => array(
					'link' => array(
						'title' => 'Link',
						'description' => 'Set the full URL for the ViewTimeline button.',
						'type' => 'line'
					),
					'link_text' => array(
						'title' => 'Link Text',
						'description' => 'Text of the button.',
						'type' => 'line'
					)
				)
			)
		),
		'icon' => 'icons/portfolio.png',
		'output_default' => 'main',
		'output' => array(
			'main' => array(
				'shortcode' => 'sportify_classes',
				'view' => 'views/classes-view',
				'shortcode_defaults' => array(
					'title' => '*Classes*',
					'description' => 'We Invite You',
					'image' => 'photos/classes-boxing.jpg',
					'content' => 'true'
				)
			)
		)
	),
	'testimonials' => array(
		'name' => 'Testimonials',
		'term' => 'testimonial',
		'term_plural' => 'testimonials',
		'order' => 'ASC',
		'has_single' => false,
		'post_options' => array('supports'=>array('title', 'editor', 'thumbnail')),
		'taxonomy_options' => array('show_ui'=>false),
		'icon' => 'icons/portfolio.png',
		'options' => array(
			'photo' => array(				
				'type' => 'image',
				'description' => 'Client photo. ',
				'title' => 'Client photo',
				'default' => 'holder.js/940x799/auto'
			)
		),
		'output_default' => 'main',
		'output' => array(
			'main' => array(
				'shortcode' => 'tesla_testimonials',
				'view' => 'views/testimonials-view',
				'shortcode_defaults' => array(
				)
			)
		)
	),
	'gallery_slider' => array(
		'name' => 'Gallery Slider',
		'term' => 'Gallery Slide',
		'term_plural' => 'Gallery Slides',
		'order' => 'ASC',
		'has_single' => false,
		'post_options' => array('supports'=>array('title', 'editor', 'thumbnail')),
		'taxonomy_options' => array('show_ui'=>false),
		'icon' => 'icons/portfolio.png',
		'options' => array(
			'photo' => array(
				'type' => 'image',
				'description' => 'Gallery photo.',
				'title' => 'Gallery photo',
				'default' => 'holder.js/940x799/auto'
			),
			'size' => array(
				'type' => 'select',
				'label' => array('original' => 'Full size', 'quarter' => 'A quarter from full size'),
				'title' => 'Select Size'
			)
		),
		'output_default' => 'main',
		'output' => array(
			'main' => array(
				'shortcode' => 'tesla_gallery_slider',
				'view' => 'views/gallery-slider-view',
				'shortcode_defaults' => array(
				)
			)
		)
	),
	'team' => array(
		'name' => 'Team',
		'term' => 'team member',
		'term_plural' => 'team members',
		'order' => 'ASC',
		'has_single' => true,
		'post_options' => array('supports'=> array( 'title', 'editor', 'excerpt','thumbnail'), 'taxonomies' => array('post_tag'), 'has_archive'=>true),
		'taxonomy_options' => array('show_ui' => true),
		'options' => array(
			'photo' => array(
				'type' => 'image',
				'description' => 'Team item cover (shown in the team grids).',
				'title' => 'Team Member Photo',
				'default' => 'holder.js/940x799/auto'
			),
			'position' => array(
				'title' => 'Position',
				'description' => 'Enter the position of this team member.',
				'type' => 'line'
			),
			'social' => array(
				'title' => 'Social Icons',
				'description' => 'Add social icons for current team member.',
				'type' => array(
					'facebook' => array(
						'title' => 'Facebook',
						'description' => 'Set the full URL to the Facebook page.',
						'type' => 'line'
					),
					'twitter' => array(
						'title' => 'Twitter',
						'description' => 'Set the full URL to the Twitter page.',
						'type' => 'line'
					),
					'pinterest' => array(
						'title' => 'Pinterest',
						'description' => 'Set the full URL to the Pinterest page.',
						'type' => 'line'
					),
					'dribbble' => array(
						'title' => 'Dribbble',
						'description' => 'Set the full URL to the Dribbble page.',
						'type' => 'line'
					),
					'youtube' => array(
						'title' => 'YouTube',
						'description' => 'Set the full URL to the YouTube page.',
						'type' => 'line'
					),
					'instagram' => array(
						'title' => 'Instagram',
						'description' => 'Set the full URL to the Instagram page.',
						'type' => 'line'
					)
				)
			)
		),
		'icon' => 'icons/portfolio.png',
		'output_default' => 'main',
		'output' => array(
			'main' => array(
				'shortcode' => 'tesla_team',
				'view' => 'views/team-view',
				'shortcode_defaults' => array(
					'title' => 'our team'
				)
			),
			'single' => array(
				'view' => 'views/team-single-view',
				'shortcode_defaults' => array(
				)
			)
		)
	),
	'events' => array(
		'name' => 'Events',
		'term' => 'Event',
		'term_plural' => 'Items In Events',
		'order' => 'ASC',
		'has_single' => true,
		'post_options' => array('supports'=> array( 'title', 'editor', 'excerpt'), 'taxonomies' => array('post_tag'),'has_archive'=>true),
		'taxonomy_options' => array('show_ui' => true),
		'options' => array(
			'photo' => array(
				'type' => 'image',
				'description' => 'Event photo.',
				'title' => 'Photo',
				'default' => 'holder.js/940x799/auto'
			),
			'more' => array(
				'title' => 'Read More',
	            'description' => 'Link for the button. Leave blank for no button at all.',
	            'type' => 'line'
			)
		),
		'icon' => 'icons/portfolio.png',
		'output' => array(
			'main' => array(
				'shortcode' => 'tesla_events',
				'view' => 'views/events-view',
				'shortcode_defaults' => array(
					'columns' => 4,
				)
			),
			'single' => array(
				'view' => 'views/events-single-view',
				'shortcode_defaults' => array(

				)
			)
		)
	),
	'video_slider' => array(
		'name' => 'Video Slider',
		'term' => 'Video Slide',
		'term_plural' => 'Items In Video Slider',
		'order' => 'ASC',
		'has_single' => false,
		'post_options' => array('supports'=>array('title')),
		'taxonomy_options' => array('show_ui'=>false),
		'options' => array(
			'video' => array(
				'type' => 'line',
				'description' => 'Add The Embeded Code for Video (Shown In Slider).',
				'title' => 'Slider Video'
			)
		),
		'icon' => 'icons/portfolio.png',
		'output' => array(
			'main' => array(
				'shortcode' => 'tesla_events',
				'view' => 'views/video-slider-view',
				'shortcode_defaults' => array(
					'columns' => 4,
				)
			)
		)
	),
	'about_us' => array(
		'name' => 'About Slider',
		'term' => 'About Slide',
		'term_plural' => 'Items In About Us Slider',
		'order' => 'ASC',
		'has_single' => false,
		'post_options' => array('supports'=>array('title', 'editor')),
		'taxonomy_options' => array('show_ui'=>false),
		'options' => array(
			'photo' => array(
				'type' => 'image',
				'description' => 'Slider Photo .',
				'title' => 'Slider Photo',
				'default' => 'holder.js/940x799/auto'
			),
			'title' => array(
				'type' => 'line',
				'title' => 'Slide Title'
			)
		),
		'icon' => 'icons/portfolio.png',
		'output' => array(
			'main' => array(
				'shortcode' => 'sportify_about_slider',
				'view' => 'views/about-slider-view',
				'shortcode_defaults' => array(
					'title' => ''
				)
			)
		)
	),
	'partners' => array(
		'name' => 'Partners',
		'term' => 'partner',
		'term_plural' => 'partners',
		'order' => 'ASC',
		'has_single' => false,
		'post_options' => array('supports'=>array('title')),
		'taxonomy_options' => array('show_ui'=>false),
		'options' => array(
			'logo' => array(
				'type' => 'image',
				'description' => 'Friend Logo .',
				'title' => 'Friend Logo',
				'default' => 'holder.js/940x799/auto'
			),
			'url' => array(
				'type' => 'line',
				'description' => 'Set the full URL for the partner logo',
				'title' => 'Partner Logo'
			)
		),
		'icon' => 'icons/portfolio.png',
		'output_default' => 'main',
		'output' => array(
			'main' => array(
				'shortcode' => 'tesla_friends',
				'view' => 'views/friends-view',
				'shortcode_defaults' => array(
					'title' => 'Our Friends'
				)
			)
		)
	),
	'price' => array(
		'name' => 'Pricing Tables',
		'term' => 'Pricing table',
		'term_plural' => 'Pricing tables',
		'order' => 'ASC',
		'has_single' => false,
		'options' => array(
			'price' => array(
				'title' => 'Price',
				'description' => 'Set the price for current table.',
				'type' => 'line'
			),
			'link' => array(
				'title' => 'Link',
				'description' => 'Set the full URL for the buy button.',
				'type' => 'line'
			),
			'link_text' => array(
				'title' => 'Link Text',
				'description' => 'Text of the button.',
				'type' => 'line'
			),
			'outlined' => array(
				'title' => 'Outline',
				'type' => 'checkbox',
				'label' => array('outlined'=>'Outline this table (make it stand out)')
			),
			'features' => array(
				'title' => 'Options',
				'description' => 'Add options for current table.',
				'type' => 'line',
				'multiple' => true
			)
		),
		'icon' => 'icons/portfolio.png',
		'output_default' => 'main',
		'output' => array(
			'main' => array(
				'shortcode' => 'sportify_pricing_tables',
				'view' => 'views/price-view',
				'shortcode_defaults' => array(
					'title' => '',
					'size' => 3
				)
			)
		)
	),
	'timeline' => array(
		'name' => 'Timeline Events',
		'term' => 'Timeline Event',
		'term_plural' => 'Events In Timeline',
		'order' => 'ASC',
		'has_single' => false,
		'post_options' => array('supports'=>array('title', 'editor','id')),
		'taxonomy_options' => array('show_ui'=>true), // show the categories for the post
		'options' => array(
			'event' => array(
				'title' => 'Event',
				'description' => 'Create the Event, will be displayed in Timeline.',
				'type' => array(
					'title' => array(
						'type' => 'line',
						'title' => 'Slide Instructor'
					),
					'location' => array(
						'type' => 'line',
						'title' => 'Slide Location'
					),
					'checked' => array(
						'type' => 'checkbox',
						'label' => array('checked'=>'Check to be see selected event in timeline'),
						'title' => 'Marked Event'
					),
					'days' => array(
						'type' => 'select',
						'label' => array('Select weekday' => 'Select weekday', 'Sunday' => 'Sunday', 'Monday' => 'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday'),
						'title' => 'Select Weekday'
					),
					'start_hour' => array(
			            'type' => 'line',
						'title' => 'Specify at what Hour (hh) the Event Starts',
			            'description' => 'hh'
					),
					'start_min' => array(
			            'type' => 'line',
						'title' => 'Specify at what Minute (mm) the Event Starts',
			            'description' => 'mm'
					),
					'end_hour' => array(
			            'type' => 'line',
						'title' => 'Specify in what Hour (hh) the Event Ends',
			            'description' => 'hh'
					),
					'end_min' => array(
			            'type' => 'line',
						'title' => 'Specify at what Minute (mm) the Event Ends',
			            'description' => 'mm'
					),
					'description' => array(
						'type' => 'text',
						'title' => 'Description',
						'description' => ''	
					)
				),
				'multiple' => true
			)
		),
		'icon' => 'icons/portfolio.png',
		'output' => array(
			'main' => array(
				'shortcode' => 'sportify_timeline_events',
				'view' => 'views/timeline-view',
				'shortcode_defaults' => array(
					'title' => '',
					'start_hour' => '4',
					'end_hour' => '21',
					'weekdays' => ''
				)
			),
			'secondary' => array(
				'shortcode' => 'sportify_programs',
				'view' => 'views/programs-view',
				'shortcode_defaults' => array(
					'title' => '',
					'link'	=> '',
					'max_number' => '4',
					'description' => ''
				)
			)
		)
	),
	'shop_offers' => array(
        'name' => 'Shop Offers',
        'term' => 'Shop offer',
        'term_plural' => 'Shop Offers',
        'order' => 'ASC',
        'has_single' => false,
        'post_options' => array('supports'=> array( 'title', 'editor','thumbnail')),
        'taxonomy_options' => array('show_ui'=>false),
        'options' => array(
            'button'  => array(
            	'type' => array(
        			'link' => array(
		                'type' => 'line',
        				'title' => 'Button Link',
		                'description' => 'Set the link for the Call To Action button.'
    				),
    				'text' => array(
		                'type' => 'line',
        				'title' => 'Button Text',
		                'description' => 'Set the text for the Call To Action button.'
    				)
        		)
            ),
            'color' => array(
                'type'  => 'color',
                'title' => 'Color',
                'default'   => '',
                'description'   => 'Choose color for current Shop offer'
            )
        ),
        'icon' => 'icons/portfolio.png',
        'output_default' => 'main',
        'output' => array(
            'main' => array(
                'shortcode' => 'sportify_shop_offers',
                'view' => 'views/shop-offers-view',
                'shortcode_defaults' => array(
                    'title'=>'',
                    'image' => 'http://teslathemes.com/demo/wp/sportify/wp-content/uploads/2014/10/trainers-background.jpg'
                )
            )
        )
    ),
	'shop_banner' => array(
        'name' => 'Shop Banner',
        'term' => 'Shop banner',
        'term_plural' => 'Shop Banner',
        'order' => 'ASC',
        'has_single' => false,
        'post_options' => array('supports'=> array( 'title')),
        'taxonomy_options' => array('show_ui'=>false),
        'options' => array(
        	'image' => array(
	            'type' => 'image',
				'default' => 'holder.js/940x799/auto',
				'title' => 'Set Image for Banner',
	            'description' => ''
			),
			'link' => array(
	            'type' => 'line',
				'title' => 'Add Banner Link ',
	            'description' => ''
			)
        ),
        'icon' => 'icons/portfolio.png',
        'output_default' => 'main',
        'output' => array(
            'main' => array(
                'shortcode' => 'sportify_shop_banner',
                'view' => 'views/shop-banner-view',
                'shortcode_defaults' => array(
                    'title'=>''
                )
            )
        )
    )
);