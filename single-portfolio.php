<?php get_header(); ?>

<main class="single-project-container" style="padding-top: 140px; min-height: 80vh;">
    <?php while(have_posts()): the_post(); ?>
        <article class="project-detail-content" style="max-width: 1400px; margin: 0 auto; padding: 0 5%;">
            <div class="reveal active">
                <p class="section-label">Collection Item</p>
                <h1 class="section-title" style="margin-bottom: 2rem;"><?php the_title(); ?></h1>
            </div>

            <?php
            $images = [];
            $content = get_the_content();

            // 1. Gallery block images
            if (has_block('gallery', $content)) {
                $blocks = parse_blocks($content);
                foreach ($blocks as $block) {
                    if ($block['blockName'] === 'core/gallery' && !empty($block['attrs']['ids'])) {
                        foreach ($block['attrs']['ids'] as $id) {
                            $images[] = [
                                'full'  => wp_get_attachment_image_url($id, 'full'),
                                'thumb' => wp_get_attachment_image_url($id, 'thumbnail'),
                                'alt'   => get_post_meta($id, '_wp_attachment_image_alt', true)
                            ];
                        }
                    }
                }
            }

            // 2. Attached media
            if (empty($images)) {
                $attachments = get_attached_media('image', get_the_ID());
                foreach ($attachments as $attachment) {
                    $images[] = [
                        'full'  => wp_get_attachment_image_url($attachment->ID, 'full'),
                        'thumb' => wp_get_attachment_image_url($attachment->ID, 'thumbnail'),
                        'alt'   => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true)
                    ];
                }
            }

            // 3. Scan content for img tags
            if (empty($images)) {
                preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $content, $matches);
                if (!empty($matches[1])) {
                    foreach ($matches[1] as $img_url) {
                        $attachment_id = attachment_url_to_postid($img_url);
                        if ($attachment_id) {
                            $images[] = [
                                'full'  => wp_get_attachment_image_url($attachment_id, 'full'),
                                'thumb' => wp_get_attachment_image_url($attachment_id, 'thumbnail'),
                                'alt'   => get_post_meta($attachment_id, '_wp_attachment_image_alt', true)
                            ];
                        } else {
                            $images[] = ['full' => $img_url, 'thumb' => $img_url, 'alt' => ''];
                        }
                    }
                }
            }

            // 4. Fallback to featured image
            if (empty($images) && has_post_thumbnail()) {
                $images[] = [
                    'full'  => get_the_post_thumbnail_url(null, 'full'),
                    'thumb' => get_the_post_thumbnail_url(null, 'thumbnail'),
                    'alt'   => get_the_title()
                ];
            }
            ?>

            <?php if (!empty($images)) : ?>
            <div class="project-carousel reveal active">
                <div class="carousel-main">
                    <button class="carousel-arrow carousel-prev" aria-label="Previous image">←</button>
                    <div class="carousel-main-image-wrapper">
                        <img id="carouselMainImage" src="<?php echo esc_url($images[0]['full']); ?>" alt="<?php echo esc_attr($images[0]['alt']); ?>" class="carousel-main-image">
                    </div>
                    <button class="carousel-arrow carousel-next" aria-label="Next image">→</button>
                </div>
                
                <?php if (count($images) > 1) : ?>
                <div class="carousel-thumbnails-wrapper">
                    <div class="carousel-thumbnails">
                        <?php foreach ($images as $index => $img) : ?>
                            <div class="carousel-thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                                <img src="<?php echo esc_url($img['thumb']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" loading="lazy">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <?php
            // Pass image URLs to JavaScript using wp_add_inline_script
            $image_urls = array_column($images, 'full');
            wp_add_inline_script('artistique-script', 'const portfolioImages = ' . json_encode($image_urls) . ';', 'before');
            ?>
            <?php endif; ?>

            <div class="project-description reveal active" style="font-size: 1.1rem; line-height: 1.8; margin-top: 4rem;">
                <?php the_content(); ?>
            </div>

            <div class="project-footer reveal active" style="margin-top: 4rem; padding-top: 2rem; border-top: 1px solid var(--color-beige);">
                <a href="<?php echo get_post_type_archive_link('portfolio'); ?>" class="btn">← Back to Gallery</a>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
