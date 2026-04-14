<?php get_header(); ?>

<main class="single-project-container" style="padding-top: 140px; min-height: 80vh;">
    <?php while(have_posts()): the_post(); ?>
        <article class="project-detail-content" style="max-width: 1400px; margin: 0 auto; padding: 0 5%;">
            <div class="reveal active">
                <p class="section-label">Collection Item</p>
                <h1 class="section-title" style="margin-bottom: 2rem;"><?php the_title(); ?></h1>
            </div>

            <?php
            // --- Collect images from multiple sources ---
            $images = [];
            $content = get_the_content();

            // 1. Try to extract gallery block images first
            if (has_block('gallery', $content)) {
                $blocks = parse_blocks($content);
                foreach ($blocks as $block) {
                    if ($block['blockName'] === 'core/gallery') {
                        if (!empty($block['attrs']['ids'])) {
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
            }

            // 2. If no gallery images, fallback to attached media
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

            // 3. If still empty, scan content for <img> tags
            if (empty($images)) {
                preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $content, $matches);
                if (!empty($matches[1])) {
                    foreach ($matches[1] as $img_url) {
                        // Try to get attachment ID from URL
                        $attachment_id = attachment_url_to_postid($img_url);
                        if ($attachment_id) {
                            $images[] = [
                                'full'  => wp_get_attachment_image_url($attachment_id, 'full'),
                                'thumb' => wp_get_attachment_image_url($attachment_id, 'thumbnail'),
                                'alt'   => get_post_meta($attachment_id, '_wp_attachment_image_alt', true)
                            ];
                        } else {
                            // External or unattached image: use the URL directly (no thumbnail fallback)
                            $images[] = [
                                'full'  => $img_url,
                                'thumb' => $img_url, // Use same URL for thumbnail
                                'alt'   => ''
                            ];
                        }
                    }
                }
            }

            // 4. Last resort: featured image only
            if (empty($images) && has_post_thumbnail()) {
                $images[] = [
                    'full'  => get_the_post_thumbnail_url(null, 'full'),
                    'thumb' => get_the_post_thumbnail_url(null, 'thumbnail'),
                    'alt'   => get_the_title()
                ];
            }
            ?>

            <?php if (!empty($images)) : ?>
            <!-- Carousel Section -->
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
                                <img src="<?php echo esc_url($img['thumb']); ?>" alt="<?php echo esc_attr($img['alt']); ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
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

<?php if (!empty($images)) : ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const images = <?php echo json_encode(array_column($images, 'full')); ?>;
    const alts = <?php echo json_encode(array_column($images, 'alt')); ?>;
    
    const mainImg = document.getElementById('carouselMainImage');
    const thumbnails = document.querySelectorAll('.carousel-thumbnail');
    const prevBtn = document.querySelector('.carousel-prev');
    const nextBtn = document.querySelector('.carousel-next');
    
    if (!mainImg || images.length === 0) return;
    
    let currentIndex = 0;
    
    function updateCarousel(index) {
        if (index < 0) index = images.length - 1;
        if (index >= images.length) index = 0;
        
        currentIndex = index;
        mainImg.src = images[index];
        mainImg.alt = alts[index];
        
        thumbnails.forEach((thumb, i) => {
            thumb.classList.toggle('active', i === index);
        });
    }
    
    if (prevBtn && nextBtn) {
        prevBtn.addEventListener('click', () => updateCarousel(currentIndex - 1));
        nextBtn.addEventListener('click', () => updateCarousel(currentIndex + 1));
    }
    
    thumbnails.forEach((thumb, i) => {
        thumb.addEventListener('click', () => updateCarousel(i));
    });
    
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
            updateCarousel(currentIndex - 1);
        } else if (e.key === 'ArrowRight') {
            updateCarousel(currentIndex + 1);
        }
    });
});
</script>
<?php endif; ?>

<?php get_footer(); ?>