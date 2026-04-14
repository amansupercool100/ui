        // ─── Custom Cursor ───────────────────────────────────────────
        const cursor = document.getElementById('cursor');
        const follower = document.getElementById('cursorFollower');
        let mouseX = 0, mouseY = 0, followerX = 0, followerY = 0;

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

        // ─── Hero parallax ───────────────────────────────────────────
        const heroImage = document.getElementById('heroImage');
        setTimeout(() => heroImage.classList.add('loaded'), 100);

        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;
            if (scrolled < window.innerHeight) {
                heroImage.style.transform = `translateY(${scrolled * 0.25}px) scale(1)`;
            }
        });

        // ─── Mobile menu ─────────────────────────────────────────────
        const menuToggle = document.getElementById('menuToggle');
        const navLinks = document.getElementById('navLinks');

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

        // ─── Navbar scroll + active link ─────────────────────────────
        const navbar = document.getElementById('navbar');
        const sections = document.querySelectorAll('section[id]');
        const navLinkEls = document.querySelectorAll('.nav-links a');

        window.addEventListener('scroll', () => {
            // Scrolled class
            navbar.classList.toggle('scrolled', window.scrollY > 80);

            // Active link highlighting
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
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, { threshold: 0.12 });

        revealEls.forEach(el => observer.observe(el));

        // ─── Testimonial Slider ───────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('testimonialTrack');
    const dots = document.querySelectorAll('.testimonial-dot');
    const testimonials = document.querySelectorAll('.testimonial');
    
    // Stop here if there are no testimonials to avoid console errors
    if (!track || testimonials.length === 0) return;

    let currentIndex = 0;

    function updateSlider(index) {
        // Remove active class from all
        testimonials.forEach(t => t.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));

        // Calculate move - ensure it's percentage based for responsiveness
        const offset = index * -100;
        track.style.transform = `translateX(${offset}%)`;

        // Add active class to current
        testimonials[index].classList.add('active');
        if(dots[index]) dots[index].classList.add('active');
        
        currentIndex = index;
    }

    // Attach click events to dots
    dots.forEach((dot) => {
        dot.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            updateSlider(index);
        });
    });

    // Auto-scroll every 5 seconds
    setInterval(() => {
        currentIndex = (currentIndex + 1) % testimonials.length;
        updateSlider(currentIndex);
    }, 5000);
});
        // ─── Form Submit ──────────────────────────────────────────────
        function handleFormSubmit(btn) {
            btn.textContent = 'Sending…';
            btn.disabled = true;
            setTimeout(() => {
                btn.textContent = 'Message Sent ✓';
                btn.style.borderColor = 'var(--color-taupe)';
                btn.style.color = 'var(--color-taupe)';
            }, 1200);
        }
    