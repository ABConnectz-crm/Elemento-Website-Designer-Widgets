/**
 * Animation Utilities for Elementor GSAP Widgets
 */
(function($) {
    'use strict';

    // Create EGW namespace
    window.EGW = window.EGW || {};

    /**
     * Animation Utilities Object
     */
    window.EGW.AnimationUtils = {

        /**
         * Initialize all animations
         */
        init: function() {
            this.initBasicAnimations();
            this.initTextAnimations();
            this.initImageAnimations();
            this.initBackgroundAnimations();
            this.initUIElements();
        },

        /**
         * Initialize basic animations
         */
        initBasicAnimations: function() {
            const elements = document.querySelectorAll('[data-egw-animation]:not(.egw-animated)');

            elements.forEach(element => {
                this.animateElement(element);
                element.classList.add('egw-animated');
            });
        },

        /**
         * Animate single element
         */
        animateElement: function(element) {
            const animationType = element.getAttribute('data-egw-animation') || 'fade-in';
            const duration = parseFloat(element.getAttribute('data-egw-duration')) || 1;
            const delay = parseFloat(element.getAttribute('data-egw-delay')) || 0;
            const easing = element.getAttribute('data-egw-easing') || 'power2.out';
            const enableScrollTrigger = element.getAttribute('data-egw-scrolltrigger') === 'true';

            // Get animation values
            const animValues = this.getAnimationValues(animationType);

            // Base animation config
            const animConfig = {
                ...animValues.to,
                duration: duration,
                delay: delay,
                ease: easing,
            };

            // Add ScrollTrigger if enabled
            if (enableScrollTrigger && typeof ScrollTrigger !== 'undefined') {
                animConfig.scrollTrigger = this.getScrollTriggerConfig(element);
            }

            // Set initial state
            gsap.set(element, animValues.from);

            // Animate
            gsap.to(element, animConfig);
        },

        /**
         * Get animation values based on type
         */
        getAnimationValues: function(type) {
            const animations = {
                'fade-in': {
                    from: { opacity: 0 },
                    to: { opacity: 1 }
                },
                'slide-up': {
                    from: { opacity: 0, y: 100 },
                    to: { opacity: 1, y: 0 }
                },
                'slide-down': {
                    from: { opacity: 0, y: -100 },
                    to: { opacity: 1, y: 0 }
                },
                'slide-left': {
                    from: { opacity: 0, x: 100 },
                    to: { opacity: 1, x: 0 }
                },
                'slide-right': {
                    from: { opacity: 0, x: -100 },
                    to: { opacity: 1, x: 0 }
                },
                'scale-up': {
                    from: { opacity: 0, scale: 0.5 },
                    to: { opacity: 1, scale: 1 }
                },
                'scale-down': {
                    from: { opacity: 0, scale: 1.5 },
                    to: { opacity: 1, scale: 1 }
                },
                'rotate-in': {
                    from: { opacity: 0, rotation: -180 },
                    to: { opacity: 1, rotation: 0 }
                },
                'blur-in': {
                    from: { opacity: 0, filter: 'blur(10px)' },
                    to: { opacity: 1, filter: 'blur(0px)' }
                },
                'clip-reveal': {
                    from: { clipPath: 'inset(0 100% 0 0)' },
                    to: { clipPath: 'inset(0 0% 0 0)' }
                }
            };

            return animations[type] || animations['fade-in'];
        },

        /**
         * Get ScrollTrigger configuration
         */
        getScrollTriggerConfig: function(element) {
            const start = element.getAttribute('data-egw-trigger-start') || 'top 80%';
            const end = element.getAttribute('data-egw-trigger-end') || 'bottom 20%';
            const scrub = element.getAttribute('data-egw-scrub');
            const pin = element.getAttribute('data-egw-pin') === 'true';
            const toggleActions = element.getAttribute('data-egw-toggleactions') || 'play none none none';
            const markers = element.getAttribute('data-egw-markers') === 'true';

            const config = {
                trigger: element,
                start: start,
                end: end,
                toggleActions: toggleActions,
                markers: markers,
            };

            if (scrub) {
                config.scrub = parseFloat(scrub);
            }

            if (pin) {
                config.pin = true;
            }

            return config;
        },

        /**
         * Initialize text animations
         */
        initTextAnimations: function() {
            // Staggered text animations
            const staggeredTexts = document.querySelectorAll('[data-egw-stagger]:not(.egw-stagger-initialized)');

            staggeredTexts.forEach(element => {
                this.animateStaggeredText(element);
                element.classList.add('egw-stagger-initialized');
            });

            // Split text animations
            const splitTexts = document.querySelectorAll('[data-egw-split-text]:not(.egw-split-initialized)');

            splitTexts.forEach(element => {
                this.animateSplitText(element);
                element.classList.add('egw-split-initialized');
            });
        },

        /**
         * Animate staggered text
         */
        animateStaggeredText: function(element) {
            const staggerAmount = parseFloat(element.getAttribute('data-egw-stagger')) || 0.05;
            const staggerFrom = element.getAttribute('data-egw-stagger-from') || 'start';
            const preserveWords = element.getAttribute('data-egw-preserve-words') === 'true';
            const animationType = element.getAttribute('data-egw-animation') || 'fade-in';
            const duration = parseFloat(element.getAttribute('data-egw-duration')) || 1;
            const easing = element.getAttribute('data-egw-easing') || 'power2.out';

            // Get child elements to animate
            let targets = element.querySelectorAll('.egw-anim-item');

            if (targets.length === 0) {
                // If no pre-wrapped items, wrap words or characters
                if (preserveWords) {
                    this.wrapWords(element);
                } else {
                    this.wrapCharacters(element);
                }
                targets = element.querySelectorAll('.egw-anim-item');
            }

            // Get animation values
            const animValues = this.getAnimationValues(animationType);

            // Set initial state
            gsap.set(targets, animValues.from);

            // Animate with stagger
            const animConfig = {
                ...animValues.to,
                duration: duration,
                ease: easing,
                stagger: {
                    amount: staggerAmount * targets.length,
                    from: staggerFrom,
                }
            };

            // Add ScrollTrigger if enabled
            if (element.getAttribute('data-egw-scrolltrigger') === 'true' && typeof ScrollTrigger !== 'undefined') {
                animConfig.scrollTrigger = this.getScrollTriggerConfig(element);
            }

            gsap.to(targets, animConfig);
        },

        /**
         * Wrap words for animation
         */
        wrapWords: function(element) {
            const text = element.textContent;
            const words = text.split(/\s+/);
            const wrapped = words.map(word => {
                if (word.trim()) {
                    return `<span class="egw-anim-item" style="display: inline-block; white-space: nowrap;">${word}</span>`;
                }
                return '';
            }).join(' ');
            element.innerHTML = wrapped;
        },

        /**
         * Wrap characters for animation
         */
        wrapCharacters: function(element) {
            const text = element.textContent;
            const chars = text.split('');
            const wrapped = chars.map(char => {
                if (char === ' ') {
                    return ' ';
                }
                return `<span class="egw-anim-item" style="display: inline-block;">${char}</span>`;
            }).join('');
            element.innerHTML = wrapped;
        },

        /**
         * Animate split text (requires SplitText plugin)
         */
        animateSplitText: function(element) {
            if (typeof SplitText === 'undefined') {
                console.warn('SplitText plugin is required for split text animations');
                return;
            }

            const splitType = element.getAttribute('data-egw-split-type') || 'words';
            const animationType = element.getAttribute('data-egw-animation') || 'fade-in';
            const duration = parseFloat(element.getAttribute('data-egw-duration')) || 1;
            const stagger = parseFloat(element.getAttribute('data-egw-stagger')) || 0.05;
            const easing = element.getAttribute('data-egw-easing') || 'power2.out';

            // Split text
            const split = new SplitText(element, { type: splitType, wordsClass: 'egw-word', charsClass: 'egw-char' });

            // Get animation values
            const animValues = this.getAnimationValues(animationType);

            // Set initial state
            gsap.set(split[splitType], animValues.from);

            // Animate
            const animConfig = {
                ...animValues.to,
                duration: duration,
                ease: easing,
                stagger: stagger,
            };

            if (element.getAttribute('data-egw-scrolltrigger') === 'true' && typeof ScrollTrigger !== 'undefined') {
                animConfig.scrollTrigger = this.getScrollTriggerConfig(element);
            }

            gsap.to(split[splitType], animConfig);
        },

        /**
         * Initialize image animations
         */
        initImageAnimations: function() {
            // Parallax images
            const parallaxImages = document.querySelectorAll('[data-egw-parallax]:not(.egw-parallax-initialized)');

            parallaxImages.forEach(element => {
                this.animateParallax(element);
                element.classList.add('egw-parallax-initialized');
            });

            // Reveal images
            const revealImages = document.querySelectorAll('[data-egw-reveal]:not(.egw-reveal-initialized)');

            revealImages.forEach(element => {
                this.animateReveal(element);
                element.classList.add('egw-reveal-initialized');
            });
        },

        /**
         * Animate parallax effect
         */
        animateParallax: function(element) {
            if (typeof ScrollTrigger === 'undefined') return;

            const speed = parseFloat(element.getAttribute('data-egw-parallax-speed')) || 0.5;
            const direction = element.getAttribute('data-egw-parallax-direction') || 'vertical';

            const movement = direction === 'horizontal' ? { x: -100 * speed } : { y: -100 * speed };

            gsap.to(element, {
                ...movement,
                ease: 'none',
                scrollTrigger: {
                    trigger: element,
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: true,
                }
            });
        },

        /**
         * Animate reveal effect
         */
        animateReveal: function(element) {
            const direction = element.getAttribute('data-egw-reveal-direction') || 'left';
            const duration = parseFloat(element.getAttribute('data-egw-duration')) || 1.5;
            const easing = element.getAttribute('data-egw-easing') || 'power4.inOut';

            const clipPaths = {
                'left': { from: 'inset(0 100% 0 0)', to: 'inset(0 0% 0 0)' },
                'right': { from: 'inset(0 0 0 100%)', to: 'inset(0 0% 0 0%)' },
                'top': { from: 'inset(0 0 100% 0)', to: 'inset(0 0% 0% 0)' },
                'bottom': { from: 'inset(100% 0 0 0)', to: 'inset(0% 0 0 0)' },
            };

            const clipPath = clipPaths[direction] || clipPaths['left'];

            gsap.set(element, { clipPath: clipPath.from });

            const animConfig = {
                clipPath: clipPath.to,
                duration: duration,
                ease: easing,
            };

            if (element.getAttribute('data-egw-scrolltrigger') === 'true' && typeof ScrollTrigger !== 'undefined') {
                animConfig.scrollTrigger = this.getScrollTriggerConfig(element);
            }

            gsap.to(element, animConfig);
        },

        /**
         * Initialize background animations
         */
        initBackgroundAnimations: function() {
            // Gradient backgrounds
            const gradientBgs = document.querySelectorAll('[data-egw-gradient-anim]:not(.egw-gradient-initialized)');

            gradientBgs.forEach(element => {
                this.animateGradient(element);
                element.classList.add('egw-gradient-initialized');
            });
        },

        /**
         * Animate gradient background
         */
        animateGradient: function(element) {
            const duration = parseFloat(element.getAttribute('data-egw-gradient-duration')) || 3;

            // Simple gradient animation using CSS custom properties
            gsap.to(element, {
                '--gradient-angle': '360deg',
                duration: duration,
                repeat: -1,
                ease: 'none',
            });
        },

        /**
         * Initialize UI elements
         */
        initUIElements: function() {
            // Icon boxes
            const iconBoxes = document.querySelectorAll('[data-egw-icon-box]:not(.egw-icon-initialized)');

            iconBoxes.forEach(element => {
                this.initIconBox(element);
                element.classList.add('egw-icon-initialized');
            });

            // Flip boxes
            const flipBoxes = document.querySelectorAll('[data-egw-flip-box]:not(.egw-flip-initialized)');

            flipBoxes.forEach(element => {
                this.initFlipBox(element);
                element.classList.add('egw-flip-initialized');
            });
        },

        /**
         * Initialize icon box
         */
        initIconBox: function(element) {
            const icon = element.querySelector('.egw-icon');
            if (!icon) return;

            element.addEventListener('mouseenter', () => {
                gsap.to(icon, {
                    scale: 1.2,
                    rotation: 10,
                    duration: 0.3,
                    ease: 'back.out',
                });
            });

            element.addEventListener('mouseleave', () => {
                gsap.to(icon, {
                    scale: 1,
                    rotation: 0,
                    duration: 0.3,
                    ease: 'back.out',
                });
            });
        },

        /**
         * Initialize flip box
         */
        initFlipBox: function(element) {
            const trigger = element.getAttribute('data-egw-flip-trigger') || 'hover';
            const direction = element.getAttribute('data-egw-flip-direction') || 'horizontal';

            const rotation = direction === 'horizontal' ? { rotationY: 180 } : { rotationX: 180 };

            if (trigger === 'hover') {
                element.addEventListener('mouseenter', () => {
                    gsap.to(element.querySelector('.egw-flip-box-inner'), {
                        ...rotation,
                        duration: 0.6,
                        ease: 'power2.inOut',
                    });
                });

                element.addEventListener('mouseleave', () => {
                    gsap.to(element.querySelector('.egw-flip-box-inner'), {
                        rotationY: 0,
                        rotationX: 0,
                        duration: 0.6,
                        ease: 'power2.inOut',
                    });
                });
            }
        },

        /**
         * Refresh all animations (useful after dynamic content load)
         */
        refresh: function() {
            if (typeof ScrollTrigger !== 'undefined') {
                ScrollTrigger.refresh();
            }
            this.init();
        },

        /**
         * Kill all animations
         */
        killAll: function() {
            gsap.killTweensOf('*');
            if (typeof ScrollTrigger !== 'undefined') {
                ScrollTrigger.getAll().forEach(trigger => trigger.kill());
            }
        }
    };

    // Initialize on DOM ready
    $(document).ready(function() {
        if (typeof gsap !== 'undefined') {
            window.EGW.AnimationUtils.init();
        }
    });

    // Re-initialize on Elementor preview refresh
    if (typeof elementorFrontend !== 'undefined') {
        $(window).on('elementor/frontend/init', function() {
            elementorFrontend.hooks.addAction('frontend/element_ready/global', function() {
                setTimeout(function() {
                    window.EGW.AnimationUtils.refresh();
                }, 100);
            });
        });
    }

})(jQuery);
