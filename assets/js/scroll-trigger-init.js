/**
 * ScrollTrigger Initialization and Management
 */
(function($) {
    'use strict';

    window.EGW = window.EGW || {};

    /**
     * ScrollTrigger Manager
     */
    window.EGW.ScrollTriggerManager = {

        triggers: [],

        /**
         * Initialize all ScrollTriggers
         */
        init: function() {
            if (typeof ScrollTrigger === 'undefined') {
                console.warn('ScrollTrigger plugin is not loaded');
                return;
            }

            this.initBatchAnimations();
            this.initPinnedSections();
            this.initHorizontalScrollSections();
            this.initProgressIndicators();
        },

        /**
         * Initialize batch animations (efficient for multiple elements)
         */
        initBatchAnimations: function() {
            const batchElements = document.querySelectorAll('[data-egw-batch]:not(.egw-batch-initialized)');

            if (batchElements.length === 0) return;

            ScrollTrigger.batch(batchElements, {
                onEnter: batch => {
                    gsap.to(batch, {
                        opacity: 1,
                        y: 0,
                        stagger: 0.15,
                        duration: 1,
                        ease: 'power2.out',
                    });
                },
                once: true,
                start: 'top 80%',
            });

            batchElements.forEach(el => el.classList.add('egw-batch-initialized'));
        },

        /**
         * Initialize pinned sections
         */
        initPinnedSections: function() {
            const pinnedSections = document.querySelectorAll('[data-egw-pin-section]:not(.egw-pin-initialized)');

            pinnedSections.forEach(section => {
                const duration = section.getAttribute('data-egw-pin-duration') || '100%';
                const pinSpacing = section.getAttribute('data-egw-pin-spacing') !== 'false';

                const trigger = ScrollTrigger.create({
                    trigger: section,
                    pin: true,
                    start: 'top top',
                    end: `+=${duration}`,
                    pinSpacing: pinSpacing,
                });

                this.triggers.push(trigger);
                section.classList.add('egw-pin-initialized');
            });
        },

        /**
         * Initialize horizontal scroll sections
         */
        initHorizontalScrollSections: function() {
            const horizontalSections = document.querySelectorAll('[data-egw-horizontal-scroll]:not(.egw-horizontal-initialized)');

            horizontalSections.forEach(section => {
                const slides = section.querySelector('[data-egw-horizontal-slides]');
                if (!slides) return;

                const slideWidth = slides.scrollWidth;

                gsap.to(slides, {
                    x: -(slideWidth - section.offsetWidth),
                    ease: 'none',
                    scrollTrigger: {
                        trigger: section,
                        pin: true,
                        scrub: 1,
                        end: () => `+=${slideWidth}`,
                        invalidateOnRefresh: true,
                    }
                });

                section.classList.add('egw-horizontal-initialized');
            });
        },

        /**
         * Initialize progress indicators
         */
        initProgressIndicators: function() {
            const progressBars = document.querySelectorAll('[data-egw-progress]:not(.egw-progress-initialized)');

            progressBars.forEach(bar => {
                const target = bar.getAttribute('data-egw-progress-target');
                const targetElement = target ? document.querySelector(target) : document.body;

                gsap.to(bar, {
                    scaleX: 1,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: targetElement,
                        start: 'top top',
                        end: 'bottom bottom',
                        scrub: true,
                    }
                });

                bar.classList.add('egw-progress-initialized');
            });
        },

        /**
         * Create custom ScrollTrigger
         */
        create: function(config) {
            const trigger = ScrollTrigger.create(config);
            this.triggers.push(trigger);
            return trigger;
        },

        /**
         * Kill specific trigger
         */
        kill: function(trigger) {
            trigger.kill();
            const index = this.triggers.indexOf(trigger);
            if (index > -1) {
                this.triggers.splice(index, 1);
            }
        },

        /**
         * Kill all triggers
         */
        killAll: function() {
            this.triggers.forEach(trigger => trigger.kill());
            this.triggers = [];
            ScrollTrigger.getAll().forEach(trigger => trigger.kill());
        },

        /**
         * Refresh all triggers
         */
        refresh: function() {
            ScrollTrigger.refresh();
        },

        /**
         * Update on resize
         */
        update: function() {
            ScrollTrigger.update();
        }
    };

    // Initialize on DOM ready
    $(document).ready(function() {
        if (typeof ScrollTrigger !== 'undefined') {
            window.EGW.ScrollTriggerManager.init();
        }
    });

    // Refresh on window load (after all images loaded)
    $(window).on('load', function() {
        if (typeof ScrollTrigger !== 'undefined') {
            setTimeout(function() {
                window.EGW.ScrollTriggerManager.refresh();
            }, 100);
        }
    });

    // Update on window resize (debounced)
    let resizeTimeout;
    $(window).on('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            if (typeof ScrollTrigger !== 'undefined') {
                window.EGW.ScrollTriggerManager.refresh();
            }
        }, 250);
    });

    // Cleanup on page unload
    $(window).on('beforeunload', function() {
        if (typeof window.EGW !== 'undefined' && window.EGW.ScrollTriggerManager) {
            window.EGW.ScrollTriggerManager.killAll();
        }
    });

})(jQuery);
