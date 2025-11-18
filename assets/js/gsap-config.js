/**
 * GSAP Configuration and Global Settings
 */
(function($) {
    'use strict';

    // Wait for DOM to be ready
    $(document).ready(function() {
        // Check if GSAP is loaded
        if (typeof gsap === 'undefined') {
            console.error('GSAP is not loaded!');
            return;
        }

        // Register GSAP plugins
        if (typeof ScrollTrigger !== 'undefined') {
            gsap.registerPlugin(ScrollTrigger);
        }

        if (typeof SplitText !== 'undefined') {
            gsap.registerPlugin(SplitText);
        }

        if (typeof Flip !== 'undefined') {
            gsap.registerPlugin(Flip);
        }

        if (typeof ScrollSmoother !== 'undefined') {
            gsap.registerPlugin(ScrollSmoother);
        }

        // Global GSAP configuration
        gsap.config({
            nullTargetWarn: false,
            trialWarn: false,
            force3D: true,
        });

        // Set default easing
        gsap.defaults({
            ease: 'power2.out',
            duration: 1,
        });

        // Check for reduced motion preference
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (prefersReducedMotion) {
            // Disable all animations if user prefers reduced motion
            gsap.globalTimeline.timeScale(100);
            if (typeof ScrollTrigger !== 'undefined') {
                ScrollTrigger.config({
                    ignoreMobileResize: true
                });
            }
        }

        // ScrollTrigger configuration
        if (typeof ScrollTrigger !== 'undefined') {
            ScrollTrigger.config({
                limitCallbacks: true,
                ignoreMobileResize: true,
            });

            // Refresh ScrollTrigger on window resize (debounced)
            let resizeTimer;
            $(window).on('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    ScrollTrigger.refresh();
                }, 250);
            });

            // Refresh ScrollTrigger after images load
            $(window).on('load', function() {
                ScrollTrigger.refresh();
            });

            // Refresh ScrollTrigger on Elementor preview reload
            if (typeof elementorFrontend !== 'undefined') {
                elementorFrontend.hooks.addAction('frontend/element_ready/global', function() {
                    setTimeout(function() {
                        ScrollTrigger.refresh();
                    }, 100);
                });
            }
        }

        // Add debug mode toggle (for development)
        if (typeof egwSettings !== 'undefined' && egwSettings.debugMode) {
            console.log('EGW Debug Mode: Enabled');
            console.log('GSAP Version:', gsap.version);
            console.log('ScrollTrigger:', typeof ScrollTrigger !== 'undefined' ? 'Loaded' : 'Not Loaded');
            console.log('SplitText:', typeof SplitText !== 'undefined' ? 'Loaded' : 'Not Loaded');
        }
    });

    // Cleanup on page unload
    $(window).on('beforeunload', function() {
        if (typeof ScrollTrigger !== 'undefined') {
            ScrollTrigger.getAll().forEach(trigger => trigger.kill());
        }
        gsap.killTweensOf('*');
    });

})(jQuery);
