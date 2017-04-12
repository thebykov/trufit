<?php get_header(); ?>
        <!-- All content goes here -->
        <div class="content">
            <section id="error_box" class="box error-box box-padding"><!-- Section Trainers-->
                <div class="container">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="error-404 double-border double-vertical-padding">
                                <header>
                                    <div class="box-header-title-block">
                                        <h2 class="entry-header">
                                            <?php _go('error_title') ? _eo('error_title') : _ex('Error 404', 'error 404', 'sportify'); ?>
                                        </h2>
                                        <h3 class="entry-subheader">
                                            <?php _go('error_message') ? _eo('error_message') : _ex('Sorry! The Page You\'re Looking For Cannot Be Found', 'error 404', 'sportify'); ?>
                                        </h3>
                                    </div>
                                </header>

                                <div class="search-block header-search wrap"><!--   search -->
                                        <?php get_search_form();?>
                                    <div class="menu-button alignleft"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="sneakers-block padding-top">
                                <img src="<?php echo IMAGES ?>/sneakers.png" alt="Sneakers" />
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
<?php get_footer(); ?>
