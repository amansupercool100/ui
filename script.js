document.addEventListener('DOMContentLoaded', function() {
    // ─── Touch device detection ─────────────────────────────────────
    if ('ontouchstart' in window || navigator.maxTouchPoints > 0) {
        document.body.classList.add('touch-device');
    }

    // ─── Custom Cursor ───────────────────────────────────────────
    const cursor = document.getElementById('cursor');
    const follower = document.getElementById('cursorFollower');
    let mouseX = 0, mouseY = 0, followerX = 0, followerY = 0;

    if (cursor && follower) {
        document.addEventListener('mousemove', e => {
            mouseX = e.clientX; mouseY = e.clientY;
            cursor.style.left = mouseX + 'px';
            cursor.style.top = mouseY + 'px';
        });

        function animateFollower() {
            followerX += (mouseX - followerX) * 0.12;
            followerY += (mouseY - followerY) * 0.12;
            follower.style.left = followerX + 'px';
            follower.style.top = followerY + 'px';
            requestAnimationFrame(animateFollower);
        }
        animateFollower();

        document.querySelectorAll('a, button, .project, .service-card, .pillar').forEach(el => {
            el.addEventListener('mouseenter', () => { cursor.classList.add('hovering'); follower.classList.add('hovering'); });
            el.addEventListener('mouseleave', () => { cursor.classList.remove('hovering'); follower.classList.remove('hovering'); });
        });
    }

    // ─── Hero parallax ───────────────────────────────────────────
    const heroImage = document.getElementById('heroImage');
    if (heroImage) {
        setTimeout(() => heroImage.classList.add('loaded'), 100);

        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;
            if (scrolled < window.innerHeight) {
                heroImage.style.transform = `translateY(${scrolled * 0.25}px) scale(1)`;
            }
        });
    }

    // ─── Mobile menu ─────────────────────────────────────────────
    const menuToggle = document.getElementById('menuToggle');
    const navLinks = document.getElementById('navLinks');

    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', () => {
            menuToggle.classList.toggle('active');
            navLinks.classList.toggle('active');
        });

        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                menuToggle.classList.remove('active');
                navLinks.classList.remove('active');
            });
        });
    }

    // ─── Navbar scroll + active link ─────────────────────────────
    const navbar = document.getElementById('navbar');
    const sections = document.querySelectorAll('section[id]');
    const navLinkEls = document.querySelectorAll('.nav-links a');

    window.addEventListener('scroll', () => {
        if (navbar) navbar.classList.toggle('scrolled', window.scrollY > 80);

        let current = '';
        sections.forEach(sec => {
            if (window.scrollY >= sec.offsetTop - 200) current = sec.getAttribute('id');
        });
        navLinkEls.forEach(a => {
            a.classList.toggle('active', a.getAttribute('href') === '#' + current);
        });
    });

    // ─── Reveal on scroll ─────────────────────────────────────────
    const revealEls = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('active');
        });
    }, { threshold: 0.12 });
    revealEls.forEach(el => observer.observe(el));

    // ─── Portfolio Slider ─────────────────────────────────────────
    const pTrack = document.getElementById('portfolioTrack');
    if (pTrack) {
        const pDots = document.querySelectorAll('.portfolio-dot');
        const pTotal = pDots.length || 1;
        let pCurrent = 0;

        function portfolioGoTo(index) {
            pCurrent = (index + pTotal) % pTotal;
            pTrack.style.transform = `translateX(-${pCurrent * 100}%)`;
            pDots.forEach((d, i) => d.classList.toggle('active', i === pCurrent));
        }

        const pPrev = document.getElementById('portfolioPrev');
        const pNext = document.getElementById('portfolioNext');
        if (pPrev) pPrev.addEventListener('click', () => portfolioGoTo(pCurrent - 1));
        if (pNext) pNext.addEventListener('click', () => portfolioGoTo(pCurrent + 1));
        pDots.forEach(dot => dot.addEventListener('click', () => portfolioGoTo(+dot.dataset.index)));

        if (pTotal > 1) setInterval(() => portfolioGoTo(pCurrent + 1), 6000);
    }

    // ─── Testimonial Slider ───────────────────────────────────────
const track = document.getElementById('testimonialTrack');
const dots = document.querySelectorAll('.testimonial-dot');
const testimonials = document.querySelectorAll('.testimonial');
const prevBtn = document.getElementById('testimonialPrev');
const nextBtn = document.getElementById('testimonialNext');

if (track && testimonials.length > 0) {
    let currentIndex = 0;

    function updateSlider(index) {
        // Wrap around
        if (index < 0) index = testimonials.length - 1;
        if (index >= testimonials.length) index = 0;

        testimonials.forEach(t => t.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));

        const offset = index * -100;
        track.style.transform = `translateX(${offset}%)`;

        testimonials[index].classList.add('active');
        if (dots[index]) dots[index].classList.add('active');
        currentIndex = index;
    }

    // Dot navigation
    dots.forEach(dot => {
        dot.addEventListener('click', function() {
            updateSlider(parseInt(this.getAttribute('data-index')));
        });
    });

    // Arrow buttons
    if (prevBtn) {
        prevBtn.addEventListener('click', () => updateSlider(currentIndex - 1));
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', () => updateSlider(currentIndex + 1));
    }

    // Auto-advance
    setInterval(() => {
        updateSlider(currentIndex + 1);
    }, 5000);
}
    // ─── Portfolio Single Carousel (if present) ────────────────────
    const mainImg = document.getElementById('carouselMainImage');
    if (mainImg && typeof portfolioImages !== 'undefined') {
        const images = portfolioImages;
        const thumbnails = document.querySelectorAll('.carousel-thumbnail');
        const prevBtn = document.querySelector('.carousel-prev');
        const nextBtn = document.querySelector('.carousel-next');
        let currentIndex = 0;

        function updateCarousel(index) {
            if (index < 0) index = images.length - 1;
            if (index >= images.length) index = 0;
            currentIndex = index;
            mainImg.src = images[index];
            thumbnails.forEach((thumb, i) => thumb.classList.toggle('active', i === index));
        }

        if (prevBtn) prevBtn.addEventListener('click', () => updateCarousel(currentIndex - 1));
        if (nextBtn) nextBtn.addEventListener('click', () => updateCarousel(currentIndex + 1));
        thumbnails.forEach((thumb, i) => thumb.addEventListener('click', () => updateCarousel(i)));

        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') updateCarousel(currentIndex - 1);
            else if (e.key === 'ArrowRight') updateCarousel(currentIndex + 1);
        });
    }
});
