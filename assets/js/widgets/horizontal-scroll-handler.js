/**
 * Horizontal Scroll Section Handler
 * Vertical scroll drives horizontal movement using FREE GSAP
 */
(function($) {
    'use strict';

    class HorizontalScrollHandler extends elementorModules.frontend.handlers.Base {
        getDefaultSettings() {
            return {
                selectors: {
                    wrapper: '.egw-h-scroll-wrapper',
                    container: '.egw-h-scroll-container',
                    sections: '.egw-h-section',
                },
            };
        }

        getDefaultElements() {
            const selectors = this.getSettings('selectors');
            return {
                $wrapper: this.$element.find(selectors.wrapper),
                $container: this.$element.find(selectors.container),
                $sections: this.$element.find(selectors.sections),
            };
        }

        bindEvents() {
            this.run();
        }

        run() {
            const $wrapper = this.elements.$wrapper;
            const $container = this.elements.$container;
            const $sections = this.elements.$sections;

            if (!$wrapper.length || !$container.length || typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
                return;
            }

            const settings = {
                scrollSpeed: parseFloat($wrapper.data('egw-scroll-speed')) || 1,
                smoothScrub: parseFloat($wrapper.data('egw-smooth-scrub')) || 1,
                enableSnap: $wrapper.data('egw-enable-snap') === 'true',
            };

            this.initHorizontalScroll($wrapper[0], $container[0], $sections, settings);

            // Handle responsive
            this.setupResizeHandler($wrapper[0], $container[0], $sections, settings);
        }

        initHorizontalScroll(wrapper, container, $sections, settings) {
            // Create GSAP context
            const ctx = gsap.context(() => {
                // Calculate total scroll distance
                const sections = Array.from($sections);
                const totalWidth = sections.reduce((acc, section) => {
                    return acc + section.offsetWidth;
                }, 0);

                const scrollDistance = totalWidth - wrapper.offsetWidth;

                // Create horizontal scroll animation
                const horizontalScroll = gsap.to(container, {
                    x: -scrollDistance,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: wrapper,
                        start: 'top top',
                        end: () => `+=${scrollDistance * settings.scrollSpeed}`,
                        scrub: settings.smoothScrub,
                        pin: true,
                        anticipatePin: 1,
                        invalidateOnRefresh: true,
                        snap: settings.enableSnap ? {
                            snapTo: 1 / (sections.length - 1),
                            duration: { min: 0.2, max: 0.5 },
                            ease: 'power1.inOut'
                        } : false,
                    }
                });

                // Add parallax effect to sections (optional enhancement)
                sections.forEach((section, index) => {
                    const content = section.querySelector('.egw-section-content');
                    if (content) {
                        gsap.fromTo(content,
                            {
                                x: index % 2 === 0 ? 100 : -100,
                                opacity: 0
                            },
                            {
                                x: 0,
                                opacity: 1,
                                scrollTrigger: {
                                    trigger: section,
                                    containerAnimation: horizontalScroll,
                                    start: 'left right',
                                    end: 'center center',
                                    scrub: true,
                                }
                            }
                        );
                    }
                });

            }, wrapper);

            // Store context
            wrapper._gsapContext = ctx;
        }

        setupResizeHandler(wrapper, container, $sections, settings) {
            let resizeTimer;
            const handleResize = () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    // Kill existing context
                    if (wrapper._gsapContext) {
                        wrapper._gsapContext.kill();
                    }

                    // Kill all ScrollTriggers associated with this wrapper
                    ScrollTrigger.getAll().forEach(st => {
                        if (st.trigger === wrapper || (wrapper.contains && wrapper.contains(st.trigger))) {
                            st.kill();
                        }
                    });

                    // Refresh ScrollTrigger
                    ScrollTrigger.refresh();

                    // Reinitialize
                    this.initHorizontalScroll(wrapper, container, $sections, settings);
                }, 300);
            };

            window.addEventListener('resize', handleResize);

            // Store handler
            wrapper._resizeHandler = handleResize;
        }

        onDestroy() {
            const $wrapper = this.elements.$wrapper[0];

            if ($wrapper) {
                // Kill GSAP context
                if ($wrapper._gsapContext) {
                    $wrapper._gsapContext.kill();
                }

                // Remove resize handler
                if ($wrapper._resizeHandler) {
                    window.removeEventListener('resize', $wrapper._resizeHandler);
                }

                // Kill associated ScrollTriggers
                ScrollTrigger.getAll().forEach(st => {
                    if (st.trigger === $wrapper || ($wrapper.contains && $wrapper.contains(st.trigger))) {
                        st.kill();
                    }
                });
            }
        }
    }

    // Register the handler
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/egw-horizontal-scroll.default',
            ($element) => {
                elementorFrontend.elementsHandler.addHandler(HorizontalScrollHandler, {
                    $element,
                });
            }
        );
    });

})(jQuery);
