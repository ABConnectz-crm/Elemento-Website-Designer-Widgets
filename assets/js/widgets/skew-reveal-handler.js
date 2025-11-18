/**
 * Skew & Stagger Text Reveal Handler
 * Premium text animation with physics-based motion using FREE GSAP plugins
 */
(function($) {
    'use strict';

    class SkewRevealHandler extends elementorModules.frontend.handlers.Base {
        getDefaultSettings() {
            return {
                selectors: {
                    container: '.egw-skew-reveal-container',
                    text: '.egw-skew-reveal-text',
                },
            };
        }

        getDefaultElements() {
            const selectors = this.getSettings('selectors');
            return {
                $container: this.$element.find(selectors.container),
                $text: this.$element.find(selectors.text),
            };
        }

        bindEvents() {
            this.run();
        }

        run() {
            const $text = this.elements.$text;

            if (!$text.length || typeof gsap === 'undefined') {
                return;
            }

            // Get settings from data attributes
            const settings = {
                splitType: $text.data('egw-split-type') || 'lines',
                direction: $text.data('egw-reveal-direction') || 'up',
                skewAmount: parseFloat($text.data('egw-skew-amount')) || 7,
                rotation3d: parseFloat($text.data('egw-rotation-3d')) || -45,
                distance: parseFloat($text.data('egw-distance')) || 100,
                useClip: $text.data('egw-use-clip') === 'true',
                staggerAmount: parseFloat($text.data('egw-stagger-amount')) || 0.1,
                staggerFrom: $text.data('egw-stagger-from') || 'start',
                staggerEase: $text.data('egw-stagger-ease') || 'none',
                duration: parseFloat($text.data('egw-duration')) || 1.2,
                easing: $text.data('egw-easing') || 'power4.out',
                scrollTrigger: $text.data('egw-scrolltrigger') === 'true',
                triggerStart: $text.data('egw-trigger-start') || 'top 80%',
                scrub: $text.data('egw-scrub'),
            };

            // Initialize animation
            if (typeof window.EGWTextSplitter !== 'undefined') {
                this.initReveal($text[0], settings);
            } else {
                console.warn('EGWTextSplitter not loaded');
            }

            // Handle responsive resize
            this.setupResizeHandler($text[0], settings);
        }

        initReveal(element, settings) {
            // Create GSAP context for easy cleanup
            const ctx = gsap.context(() => {
                // Split text
                const splitter = new window.EGWTextSplitter(element, {
                    type: settings.splitType
                });

                let targets;
                switch (settings.splitType) {
                    case 'lines':
                        targets = splitter.lines;
                        break;
                    case 'words':
                        targets = splitter.words;
                        break;
                    case 'chars':
                        targets = splitter.chars;
                        break;
                    default:
                        targets = splitter.lines;
                }

                if (!targets.length) {
                    console.warn('No targets found for animation');
                    return;
                }

                // Wrap targets in overflow containers if using clip
                if (settings.useClip && settings.splitType === 'lines') {
                    targets.forEach(line => {
                        const wrapper = document.createElement('div');
                        wrapper.style.cssText = 'overflow: hidden; display: block;';
                        line.parentNode.insertBefore(wrapper, line);
                        wrapper.appendChild(line);
                    });
                }

                // Calculate initial transform values based on direction
                const fromValues = this.getFromValues(settings);
                const toValues = this.getToValues();

                // Set transform origin
                const transformOrigin = this.getTransformOrigin(settings.direction);
                gsap.set(targets, {
                    transformOrigin: transformOrigin,
                    transformStyle: 'preserve-3d'
                });

                // Build animation config
                const animConfig = {
                    ...toValues,
                    duration: settings.duration,
                    ease: settings.easing,
                    stagger: {
                        amount: settings.staggerAmount * targets.length,
                        from: settings.staggerFrom,
                        ease: settings.staggerEase
                    },
                    clearProps: 'transform,opacity' // Clean up after animation
                };

                // Add ScrollTrigger if enabled
                if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                    animConfig.scrollTrigger = {
                        trigger: element,
                        start: settings.triggerStart,
                        toggleActions: 'play none none none',
                    };

                    if (settings.scrub) {
                        animConfig.scrollTrigger.scrub = parseFloat(settings.scrub);
                    }
                }

                // Animate from initial state to final state
                gsap.from(targets, {
                    ...fromValues,
                    ...animConfig
                });

                // Store splitter and context for cleanup
                element._splitter = splitter;
                element._gsapContext = ctx;

            }, element); // Scope context to element
        }

        getFromValues(settings) {
            const values = {
                opacity: 0
            };

            // Calculate movement based on direction
            switch (settings.direction) {
                case 'up':
                    values.yPercent = 100;
                    values.rotationX = settings.rotation3d;
                    values.skewY = settings.skewAmount;
                    break;
                case 'down':
                    values.yPercent = -100;
                    values.rotationX = -settings.rotation3d;
                    values.skewY = -settings.skewAmount;
                    break;
                case 'left':
                    values.xPercent = 100;
                    values.rotationY = settings.rotation3d;
                    values.skewX = settings.skewAmount;
                    break;
                case 'right':
                    values.xPercent = -100;
                    values.rotationY = -settings.rotation3d;
                    values.skewX = -settings.skewAmount;
                    break;
            }

            return values;
        }

        getToValues() {
            return {
                opacity: 1,
                yPercent: 0,
                xPercent: 0,
                rotationX: 0,
                rotationY: 0,
                skewX: 0,
                skewY: 0
            };
        }

        getTransformOrigin(direction) {
            switch (direction) {
                case 'up':
                    return '50% 100% -50px';
                case 'down':
                    return '50% 0% -50px';
                case 'left':
                    return '100% 50% -50px';
                case 'right':
                    return '0% 50% -50px';
                default:
                    return '50% 50% -50px';
            }
        }

        setupResizeHandler(element, settings) {
            let resizeTimer;
            const handleResize = () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    // Kill existing context
                    if (element._gsapContext) {
                        element._gsapContext.kill();
                    }

                    // Revert splitter
                    if (element._splitter) {
                        element._splitter.revert();
                    }

                    // Reinitialize
                    this.initReveal(element, settings);
                }, 250);
            };

            window.addEventListener('resize', handleResize);

            // Store handler for cleanup
            element._resizeHandler = handleResize;
        }

        onDestroy() {
            const $text = this.elements.$text[0];

            if ($text) {
                // Kill GSAP context
                if ($text._gsapContext) {
                    $text._gsapContext.kill();
                }

                // Revert text split
                if ($text._splitter) {
                    $text._splitter.revert();
                }

                // Remove resize handler
                if ($text._resizeHandler) {
                    window.removeEventListener('resize', $text._resizeHandler);
                }
            }
        }
    }

    // Register the handler
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/egw-skew-reveal-text.default',
            ($element) => {
                elementorFrontend.elementsHandler.addHandler(SkewRevealHandler, {
                    $element,
                });
            }
        );
    });

})(jQuery);
