<?php
/*
	Template Name: Contacts
*/
?>
<?php get_header(); ?>
    <div class="content">
        <section id="contact_box" class="box contact-box"><!-- Section Slider -->
            <header class="text-center contact-box-header box-header">
                <div class="container">
                    <div class="white-border box-header-title-block aligncenter nowrap">
                        <h2 class="entry-header">
                            *<?php _e('Contact', 'Contacts', 'sportify') ?>*
                        </h2>
                    </div>
                    <h3 class="entry-description"><?php _e('Contact Us', 'sportify') ?></h3>
                </div>
            </header>

            <div class="gmap-container">
                <?php tt_gmap('contact_map', 'contact_map', 'contacts-map', 'false'); ?>
            </div>

            <div class="contact-form-container white-grey-background padding-bottom">
                <header class="text-center contact-box-header box-header padding-top">
                    <div class="container">
                        <div class="white-border box-header-title-block aligncenter nowrap">
                            <h2 class="entry-header">
                                *<?php _ex('Write Us', 'Contacts', 'sportify') ?>*
                            </h2>
                        </div>
                        <h3 class="entry-description"><?php _ex('Contact Us', 'Contacts', 'sportify') ?></h3>
                    </div>
                </header>

                <div class="contact-us-block">
                    <div class="container">

                        <?php if(_go('contact_form')) : ?>
                            <?php tt_form_location('contact_page'); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php get_footer(); ?>