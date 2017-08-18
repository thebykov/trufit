 <!-- ================================= END CONTENT === -->
 
<!-- ================================= START FOOTER === -->

    <footer class="main-footer grey-background">

        <!-- Footer widgets -->

        <div class="big-footer">

            <div class="container">

                <div class="row">

                    <div class="col-md-4">

                     <?php if(!is_active_sidebar('footer_1')): ?>

                        <div class="sportify-wrap">

                            <div class="sportify-block dark-border">

                                <?php if(!_go("footer_banner")): ?>

                                    <div class="row">

                                        <div class="col-md-6 col-sm-9 col-xs-8">

                                            <h1 class="text-shadow"><?php _e('Sportify', 'sportify'); ?></h1>

                                        </div>

                                        <div class="col-md-6 col-sm-3 col-xs-3">

                                            <ul class="clean-list clear-list sportify-logo-list row">

                                                <li class="sportify-logo red-background"></li>

                                                <li class="red-background medium-opacity"></li>

                                                <li class="red-background opacity"></li>

                                            </ul>

                                        </div>

                                    </div>

                                <?php else: ?><img src="<?php _eo("footer_banner"); ?>">

                                <?php endif; ?>

                            </div>

                        </div>

                        <div class="contact-info dark-background block-padding">

                            <?php if( _go('title_contact') ): ?>

                                <h2 class="entry-header"><?php _eo(('title_contact')); ?></h2>

                            <?php endif; ?>



                            <p>

                                <?php if( _go('content_address') ): ?>

                                    <?php _eo('content_address'); ?>

                                <?php endif; ?><br />

                                

                                <?php if( _go('contact_phone') ): ?>

                                    <strong><?php _eo('contact_phone') ?></strong>

                                <?php endif; ?>



                                <?php if( _go('contact_fax') ): ?>

                                    <strong><?php _eo('contact_fax') ?></strong>

                                <?php endif; ?>

                            </p>

                            <?php if( _go('email_contact') ): ?>

                                <a href="mailto:<?php _eo('email_contact') ?>" class="email"><?php _eo('email_contact'); ?></a>

                            <?php endif; ?>

                        </div>

                        <?php  else: dynamic_sidebar('footer_1'); endif;?>

                    </div>

                    <div class="col-md-8">

                        <?php if(!is_active_sidebar('footer_2')) : ?> 

                        <div class="footer-block block-padding light-border">

                            <div class="row">

                                <div class="col-md-4 col-sm-4 hidden">

                                    <nav class="footer-nav"> <!-- .mega-menu helper class ued as switcher -->

                                         <ul class="clean-list clearfix">

                                            <?php wp_nav_menu( array( 

                                                'title_li'=> '',

                                                'theme_location' => 'secondary',

                                                'container' => false,

                                                'items_wrap' => '%3$s',

                                                'fallback_cb' => 'wp_list_pages'

                                            )); ?>

                                        </ul>

                                    </nav>

                                </div>

                                <div class="col-md-12 col-sm-12">

                                    

                                    <div class="gmap-container">

                                            <?php tt_gmap('contact_map', 'contacts_map', 'contact-map', 'false'); ?>

                                    </div>

                                    <div class="contact-block margin-top">

                                         <?php if(_go('contact_form')) : ?>

                                            <?php tt_form_location('footer'); ?>

                                        <?php endif; ?> 

                                    </div>

                                </div>

                            </div>

                        </div>

                        <?php else: dynamic_sidebar('footer_2'); endif?>

                    </div>

                </div>

            </div>

        </div>

        <div class="small-footer">

            <div class="container">

                <div class="row">

                    <div class="text-center copyright">

                        <?php if( _go('copyright_message')): ?>

                            <p><?php _eo('copyright_message'); ?></p>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

    </footer>

<!-- ================================= END FOOTER === -->      



</div>

        <?php wp_footer(); ?>


<div class="fixed-footer">
   <div class="nav-wrapper row" style="
">
   	<div class="nav-item col-xs-3">
		<a href="/">
        <i class="fa fa-home" aria-hidden="true"></i>
   		Home</a>
   	</div>
   	<div class="nav-item col-xs-3">
		<a href="/schedules/trufit-bootcamp/">
        <i class="fa fa-calendar" aria-hidden="true"></i>
   		Schedule</a>
   	</div>


<?php if( current_user_can('member') || current_user_can('special_member') || current_user_can('owner') || current_user_can('administrator') ) {  ?> 
    	<div class="nav-item col-xs-3">
		<a href="/user/">
        <i class="fa fa-user" aria-hidden="true"></i>
   		Profile</a>
   	</div>
<?php }else{ ?>

   	<div class="nav-item col-xs-3">
		<a href="/login">
        <i class="fa fa-sign-in" aria-hidden="true"></i>
   		Login</a>
   	</div>

<?php } ?>

<?php if( current_user_can('special_member') || current_user_can('owner') || current_user_can('administrator') ) {  ?> 
    	<div class="nav-item col-xs-3">
		<a href="/wods/">
        <i class="fa fa-list" aria-hidden="true"></i>
   		WODS</a>
   	</div>
<?php }else{ ?>

   	<div class="nav-item col-xs-3">
		<a href="/contact-us/">
        <i class="fa fa-envelope" aria-hidden="true"></i>
   		Contact Us</a>
   	</div>

<?php } ?>

   </div>
</div>
    </body>

</html>