<?php get_header(); ?>

<section class="portfolio-archive" style="padding-top: 140px;">
    <div class="portfolio-header">
        <div class="reveal active">
            <p class="section-label">Our Collection</p>
            <h2 class="section-title">Full Portfolio</h2>
        </div>
    </div>

    <?php if (have_posts()) : ?>
        <div class="portfolio-archive-grid reveal active">
            <?php while (have_posts()) : the_post(); ?>
                <div class="project">
                    <a href="<?php the_permalink(); ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('portfolio-archive', ['class' => 'project-thumb', 'loading' => 'lazy']); ?>
                        <?php else : ?>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/placeholder.jpg" alt="<?php the_title(); ?>" class="project-thumb" loading="lazy">
                        <?php endif; ?>
                        <div class="project-overlay">
                            <span class="project-category">Project</span>
                            <h3 class="project-title"><?php the_title(); ?></h3>
                        </div>
                        <div class="project-arrow">↗</div>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>

        <?php the_posts_pagination([
            'mid_size'  => 2,
            'prev_text' => '←',
            'next_text' => '→',
        ]); ?>
    <?php else : ?>
        <p style="text-align:center;">No portfolio items found.</p>
    <?php endif; ?>
</section>

<?php get_footer(); ?>
