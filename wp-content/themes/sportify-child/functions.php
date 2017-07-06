<?php

// Exit if accessed directly

if ( !defined( 'ABSPATH' ) ) exit;



// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

// END ENQUEUE PARENT ACTION





function register_my_menu() {

  register_nav_menu('top-menu',__( 'Top Menu' ));

}

add_action( 'init', 'register_my_menu' );


register_sidebar( array(
    'name'          => __( 'WOD', 'sportify' ),
    'id'            => 'wod-sidebar',
    'description'   => __( 'Appears on WOD posts and archive.', 'sportify' ),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>',
) );

register_sidebar( array(
    'name'          => __( 'Recipe', 'sportify' ),
    'id'            => 'recipe-sidebar',
    'description'   => __( 'Appears on Recipes posts and archive.', 'sportify' ),
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>',
) );

?>