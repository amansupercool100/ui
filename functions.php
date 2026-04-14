<?php
/**
 * Artistique Theme Setup
 */

// Enqueue styles & scripts with cache busting
function artistique_assets() {
    $css_file = get_stylesheet_directory() . '/style.css';
    $js_file  = get_template_directory() . '/script.js';

    $css_version = file_exists($css_file) ? filemtime($css_file) : '4.1';
    $js_version  = file_exists($js_file) ? filemtime($js_file) : '4.1';

    wp_enqueue_style('artistique-fonts', 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400&family=Montserrat:wght@300;400;500;600&display=swap', [], null);
    wp_enqueue_style('artistique-style', get_stylesheet_uri(), [], $css_version);
    wp_enqueue_script('artistique-script', get_template_directory_uri() . '/script.js', [], $js_version, true);
}
add_action('wp_enqueue_scripts', 'artistique_assets');

// Theme supports
add_theme_support('title-tag');
add_theme_support('post-thumbnails');
add_theme_support('menus');
add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);

// Register navigation menus
register_nav_menus(['primary' => 'Primary Menu']);

// Add custom image size for portfolio archive (3:4 aspect)
add_image_size('portfolio-archive', 600, 800, true);

// Portfolio Custom Post Type
function artistique_portfolio() {
    register_post_type('portfolio', [
        'labels'      => ['name' => 'Portfolio', 'singular_name' => 'Project'],
        'public'      => true,
        'has_archive' => true,
        'rewrite'     => ['slug' => 'portfolio'],
        'supports'    => ['title', 'editor', 'thumbnail'],
        'menu_icon'   => 'dashicons-images-alt2',
        'show_in_rest'=> true, // Gutenberg support
    ]);
}
add_action('init', 'artistique_portfolio');

// Testimonials Custom Post Type
function artistique_testimonials_cpt() {
    register_post_type('testimonials', [
        'labels'      => ['name' => 'Testimonials', 'singular_name' => 'Testimonial'],
        'public'      => true,
        'supports'    => ['title', 'editor'],
        'menu_icon'   => 'dashicons-format-quote',
        'show_in_rest'=> true,
    ]);
}
add_action('init', 'artistique_testimonials_cpt');

// Flush rewrite rules ONLY on theme activation
function artistique_theme_activation() {
    artistique_portfolio();
    artistique_testimonials_cpt();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'artistique_theme_activation');

// Open Graph meta tags for portfolio items
function artistique_opengraph_tags() {
    if (is_singular('portfolio') && has_post_thumbnail()) {
        echo '<meta property="og:image" content="' . esc_url(get_the_post_thumbnail_url(null, 'large')) . '">' . "\n";
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
    }
}
add_action('wp_head', 'artistique_opengraph_tags');
