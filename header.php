<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <div class="cursor" id="cursor"></div>
    <div class="cursor-follower" id="cursorFollower"></div>

    <nav id="navbar">
        <a href="<?php echo home_url(); ?>" class="logo">SHANU SILVER</a>
        <ul class="nav-links" id="navLinks">
            <li><a href="<?php echo home_url('#about'); ?>">About</a></li>
            <li><a href="<?php echo home_url('#services'); ?>">Services</a></li>
            <li><a href="<?php echo get_post_type_archive_link('portfolio'); ?>">Portfolio</a></li>
            <li><a href="<?php echo home_url('#contact'); ?>">Contact</a></li>
        </ul>
        <div class="menu-toggle" id="menuToggle">
            <span></span><span></span><span></span>
        </div>
    </nav>