/**
 * Advanced Clip-Path Reveal Handler
 * Awwwards-style reveals using CSS clip-path with FREE GSAP
 */
(function($) {
    'use strict';

    class ClipPathRevealHandler extends elementorModules.frontend.handlers.Base {
        getDefaultSettings() {
            return {
                selectors: {
                    wrapper: '.egw-clippath-wrapper',
                    content: '.egw-clippath-content',
                },
            };
        }

        getDefaultElements() {
            const selectors = this.getSettings('selectors');
            return {
                $wrapper: this.$element.find(selectors.wrapper),
                $content: this.$element.find(selectors.content),
            };
        }

        bindEvents() {
            this.run();
        }

        run() {
            const $wrapper = this.elements.$wrapper;
            const $content = this.elements.$content;

            if (!$wrapper.length || typeof gsap === 'undefined') {
                return;
            }

            const settings = {
                shape: $wrapper.data('egw-reveal-shape') || 'circle',
                origin: $wrapper.data('egw-reveal-origin') || 'center',
                duration: parseFloat($wrapper.data('egw-reveal-duration')) || 1.5,
                easing: $wrapper.data('egw-reveal-easing') || 'power4.inOut',
                scaleContent: $wrapper.data('egw-scale-content') === 'true',
                scrollTrigger: $wrapper.data('egw-scrolltrigger') === 'true',
                triggerStart: $wrapper.data('egw-trigger-start') || 'top 80%',
            };

            this.initReveal($wrapper[0], $content[0], settings);
        }

        initReveal(wrapper, content, settings) {
            const clipPaths = this.getClipPaths(settings.shape, settings.origin);

            // Set initial clip-path
            gsap.set(content, {
                clipPath: clipPaths.from
            });

            // Optional: scale content during reveal
            const animProps = {
                clipPath: clipPaths.to,
                duration: settings.duration,
                ease: settings.easing
            };

            if (settings.scaleContent) {
                gsap.set(content, { scale: 0.8 });
                animProps.scale = 1;
            }

            // Add ScrollTrigger if enabled
            if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                animProps.scrollTrigger = {
                    trigger: wrapper,
                    start: settings.triggerStart,
                    toggleActions: 'play none none none'
                };
            }

            // Animate
            gsap.to(content, animProps);

            // Handle mouse follow if needed
            if (settings.origin === 'mouse') {
                this.handleMouseFollow(wrapper, content, settings);
            }
        }

        getClipPaths(shape, origin) {
            const clipPaths = {
                from: '',
                to: ''
            };

            switch (shape) {
                case 'circle':
                    const originMap = {
                        'center': 'circle(0% at 50% 50%)',
                        'top-left': 'circle(0% at 0% 0%)',
                        'top-right': 'circle(0% at 100% 0%)',
                        'bottom-left': 'circle(0% at 0% 100%)',
                        'bottom-right': 'circle(0% at 100% 100%)',
                        'mouse': 'circle(0% at 50% 50%)'
                    };
                    clipPaths.from = originMap[origin] || originMap['center'];
                    clipPaths.to = clipPaths.from.replace('0%', '150%');
                    break;

                case 'polygon-diagonal':
                    clipPaths.from = 'polygon(0 0, 0 0, 0 100%)';
                    clipPaths.to = 'polygon(0 0, 100% 0, 100% 100%, 0 100%)';
                    break;

                case 'polygon-center':
                    clipPaths.from = 'polygon(50% 50%, 50% 50%, 50% 50%, 50% 50%)';
                    clipPaths.to = 'polygon(0 0, 100% 0, 100% 100%, 0 100%)';
                    break;

                case 'inset-horizontal':
                    clipPaths.from = 'inset(0 50% 0 50%)';
                    clipPaths.to = 'inset(0 0% 0 0%)';
                    break;

                case 'inset-vertical':
                    clipPaths.from = 'inset(50% 0 50% 0)';
                    clipPaths.to = 'inset(0% 0 0% 0)';
                    break;

                case 'polygon-diamond':
                    clipPaths.from = 'polygon(50% 50%, 50% 50%, 50% 50%, 50% 50%)';
                    clipPaths.to = 'polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%)';
                    break;

                case 'polygon-hexagon':
                    clipPaths.from = 'polygon(50% 50%, 50% 50%, 50% 50%, 50% 50%, 50% 50%, 50% 50%)';
                    clipPaths.to = 'polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%)';
                    break;

                default:
                    clipPaths.from = 'circle(0% at 50% 50%)';
                    clipPaths.to = 'circle(150% at 50% 50%)';
            }

            return clipPaths;
        }

        handleMouseFollow(wrapper, content, settings) {
            let mouseX = 50;
            let mouseY = 50;

            wrapper.addEventListener('mousemove', (e) => {
                const rect = wrapper.getBoundingClientRect();
                mouseX = ((e.clientX - rect.left) / rect.width) * 100;
                mouseY = ((e.clientY - rect.top) / rect.height) * 100;

                gsap.to(content, {
                    clipPath: `circle(150% at ${mouseX}% ${mouseY}%)`,
                    duration: 0.3,
                    ease: 'power2.out'
                });
            });

            wrapper.addEventListener('mouseleave', () => {
                gsap.to(content, {
                    clipPath: `circle(0% at ${mouseX}% ${mouseY}%)`,
                    duration: 0.5,
                    ease: 'power2.in'
                });
            });
        }

        onDestroy() {
            // Clean up
            gsap.killTweensOf(this.elements.$content[0]);
        }
    }

    // Register the handler
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/egw-clippath-reveal.default',
            ($element) => {
                elementorFrontend.elementsHandler.addHandler(ClipPathRevealHandler, {
                    $element,
                });
            }
        );
    });

})(jQuery);
