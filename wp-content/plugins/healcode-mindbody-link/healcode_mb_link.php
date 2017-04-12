<?php
/*
Plugin Name: HealCode MINDBODY Login
Plugin URI: https://wordpress.org/plugins/healcode-mindbody-link/
Description: Add a HealCode Link to your WordPress main navigation menu.
Version: 1.2.2
Author: HealCode
Author URI: http://www.healcode.com/
Text Domain: healcode-mb-link
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// ***********************************************************
// Adding the Top Level menu for HealCode MINDBODY Links
// ***********************************************************

function add_html_menu_item() {
    add_menu_page(
        'HealCode MINDBODY Login Settings',
        'HC Login Link',
        'manage_options',
        'html-menu-healcode',
        'menu_html_options',
        plugins_url('healcode-mindbody-link/images/hclogo.png')
    );
}

add_action('admin_menu', 'add_html_menu_item');


// *********************************************
// HTML content of the Options page
// *********************************************

// hc_settings_page() displays the page content for the Test settings submenu
function menu_html_options() {

    //must check that the user has the required capability
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page. Please contact the Administrator of this website.') );
    }

    // variables for the field and option names
    $opt_name = 'hc_service_link';
    $hidden_field_name = 'hc_submit_hidden';
    $data_field_name = 'hc_service_link';

    // Read in existing option value from database
    $opt_val = get_option( $opt_name );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val = stripslashes($_POST[ $data_field_name ]);

        // Save the posted value in the database
        update_option( $opt_name, $opt_val );

        // Put an settings updated message on the screen

?>
<div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>
<?php

    }

// *********************************************
// The HTML and form of the settings page
// *********************************************

    echo '<div class="wrap">'; ?>

    <!-- Header Links to our About page in WordPress and HealCode -->

<div style="margin-top: 10px">
<table style="float:right; ">
    <tr>

        <td style="float:right;">
            <a class="hc_header_link" style="margin-left:8px;" target="_blank" href="https://wordpress.org/plugins/healcode-mindbody-link/">About</a>
        </td>
        <td style="float:right;">
            <a class="hc_header_link" target="_blank" href="http://www.healcode.com">HealCode</a>
        </td>

    </tr>
</table>
</div>

<div style="clear: both"></div>

<?php echo "<h2>" . __( 'HealCode MINDBODY Login Settings', 'menu-test' ) . "</h2>"; ?>
<div class="hc-link-code-example">
    Paste your HealCode Link code in the form below. Once you click the 'Save Changes' button, the HealCode Link will appear in your site's menu.</br>
    To get your code, go to the Links page in your HealCode account:
    <div class="hc-add-widget">
        <img src="<?php echo plugins_url('healcode-mindbody-link/images/hc-link-code-example.png')?>" alt="link code example">
    </div>
    Your HealCode Link Code:
</div>
<!-- ----------------------------------------------- -->

<?php

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty($_POST["hc_service_link"])) {
            $opt_val = "";
        } else {
            $opt_val = test_input($_POST["hc_service_link"]);
        }
    }

?>


<!-- ----------------- Form for inputting HealCode Link code ----------------- -->

<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<textarea name="<?php echo $data_field_name; ?>" rows="5" cols="100">
    <?php echo $opt_val;?>
</textarea>
<hr />

</div>

<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>

</form>

<div class="hc-link-copy">
    <i>New to HealCode? <a href="https://www.healcode.com" target="_blank">Click to learn more about our MINDBODY integrations and try us <strong>free for 1 month</strong></a></i>
</div>

<!-- ----------------- Footer of page ----------------- -->

<div style="clear: both;"></div>
<p></p>
<div style="width: 100%">
    <div class="hc_feedback_links">
        <!-- Footer Links, including tech support and social media -->
        <a target="_blank" href="https://healcode.zendesk.com/hc/en-us/articles/203681304">Instructions</a>
        <a target="_blank" href="http://www.healcode.com/tech_support" class="">Tech Support</a>
        <a target="_blank" href="https://manager.healcode.com/users/sign_in" class="">Login to HealCode</a>
    </div>
    <p></p>
</div>
<p style="clear: both;"></p>

<!-- ----------------- Styling the submenu ----------------- -->

<style>
    a {
        text-decoration: none;
    }

    label{
        cursor:default;
    }

    .wrap, p.submit {
        margin-left:8px !important;
    }

    .hc_feedback_links{
        background: #333; /* Old browsers */
        border: 0px solid #333;
        border-radius:3px;
        width: 96%;
        height:30px;
        padding: 10px;
        font-weight: bold;
        line-height: 30px;
        margin-left:8px;
        text-align:center;
    }


    .hc_feedback_links a{
        text-decoration: none;
        color:#fff;
        padding:0px 30px;
        vertical-align:center;
        text-transform:uppercase;
    }

    a.hc_header_link {
        color:#f68f1e;
        margin-right:15px;
        font-weight:bold;
    }

   .hc-short thead tr th {
        background-color:#D2D2D2;
    }

    .hc-link-code-example {
      margin: 1em 0 1em 8px;
    }

    .hc-link-code-example .hc-add-widget {
      margin-top: 1em;
      margin-bottom: 2em;
    }

    .hc-link-code-example .hc-add-widget > img {
        width: 50%;
    }

    .hc-link-copy {
        width: 100%;
        margin: 1em 0 1em 8px;
    }
</style>

<?php
}

// *********************************************
// Adding the HTML list item to the menu
// *********************************************

add_filter('wp_nav_menu_items','add_HTML_li', 10, 2);
function add_HTML_li($item, $args) {

    $opt_val = get_option( 'hc_service_link' );
    // $items .= '<li>' . $searchform . '</li>';
	$item .= '<script src="https://widgets.healcode.com/javascripts/healcode.js" type="text/javascript"></script>' . '<li>' . stripslashes($opt_val) . '</li>';

    return $item;
} ?>
