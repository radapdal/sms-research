<?php
function my_theme_enqueue_styles() {
    $parent_style = 'twentynineteen-style';
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/css/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

function myscripts() { 
    wp_register_script('child-main', 
                        get_stylesheet_directory_uri() .'/js/main.js',   //
                        false, false);
    wp_enqueue_script('child-main');    
}
add_action("wp_enqueue_scripts", "myscripts");

