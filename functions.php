<?php
function artistique_assets() {
    wp_enqueue_style('artistique-style', get_stylesheet_uri(), [], '4.0');
    wp_enqueue_script('artistique-script', get_template_directory_uri() . '/script.js', [], '4.0', true);
}
add_action('wp_enqueue_scripts', 'artistique_assets');

add_theme_support('title-tag');
add_theme_support('post-thumbnails');

// Portfolio CPT
function artistique_portfolio() {
    register_post_type('portfolio', [
        'labels' => ['name' => 'Portfolio'],
        'public' => true,
        'has_archive' => true, // <--- ADD THIS LINE
        'rewrite' => array('slug' => 'portfolio'), // <--- ADD THIS LINE
        'supports' => ['title', 'editor', 'thumbnail'],
        'menu_icon' => 'dashicons-images-alt2'
    ]);
}
flush_rewrite_rules();
add_action('init', 'artistique_portfolio');

// Enable menus
add_theme_support('menus');
register_nav_menus(['primary' => 'Primary Menu']);

// Testimonials Custom Post Type
function artistique_testimonials_cpt() {
    register_post_type('testimonials', [
        'labels' => ['name' => 'Testimonials', 'singular_name' => 'Testimonial'],
        'public' => true,
        'supports' => ['title', 'editor'], // Title for the Name, Editor for the Quote
        'menu_icon' => 'dashicons-format-quote',
    ]);
}
add_action('init', 'artistique_testimonials_cpt');
// Temporary fix to refresh links
function manual_permalink_fix() {
    flush_rewrite_rules();
}
add_action('init', 'manual_permalink_fix');