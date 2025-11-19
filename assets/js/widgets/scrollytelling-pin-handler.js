/**
 * Scrollytelling Pin Container Handler
 * Creates Awwwards-style pinned scroll sections using FREE GSAP
 */
(function($) {
    'use strict';

    class ScrollytellingPinHandler extends elementorModules.frontend.handlers.Base {
        getDefaultSettings() {
            return {
                selectors: {
                    container: '.egw-pin-container',
                    panels: '.egw-pin-panel',
                },
            };
        }

        getDefaultElements() {
            const selectors = this.getSettings('selectors');
            return {
                $container: this.$element.find(selectors.container),
                $panels: this.$element.find(selectors.panels),
            };
        }

        bindEvents() {
            this.run();
        }

        run() {
            const $container = this.elements.$container;
            const $panels = this.elements.$panels;

            if (!$container.length || !$panels.length || typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
                return;
            }

            const settings = {
                effectType: $container.data('egw-effect-type') || 'stack',
                pinSpacer: parseFloat($container.data('egw-pin-spacer')) || 100,
                scaleAmount: parseFloat($container.data('egw-scale-amount')) || 0.9,
                stackOffset: parseFloat($container.data('egw-stack-offset')) || 40,
            };

            this.initScrollytelling($container[0], $panels, settings);

            // Handle responsive
            this.setupResizeHandler($container[0], $panels, settings);
        }

        initScrollytelling(container, $panels, settings) {
            const panels = Array.from($panels);

            // Create GSAP context for cleanup
            const ctx = gsap.context(() => {
                panels.forEach((panel, index) => {
                    // Skip the last panel (it stays in place)
                    if (index === panels.length - 1) {
                        return;
                    }

                    const nextPanel = panels[index + 1];

                    // Set initial z-index
                    gsap.set(panel, { zIndex: panels.length - index });
                    gsap.set(nextPanel, { zIndex: panels.length - index - 1 });

                    // Set initial dark state for next panel
                    gsap.set(nextPanel, { filter: 'brightness(0)' });

                    // Create animation based on effect type
                    const animation = this.createAnimation(panel, nextPanel, settings, index);

                    // Create ScrollTrigger
                    ScrollTrigger.create({
                        trigger: panel,
                        start: 'top top',
                        end: `+=${settings.pinSpacer}%`,
                        pin: panel,
                        pinSpacing: false,
                        scrub: true,
                        animation: animation,
                        invalidateOnRefresh: true,
                        onEnter: () => {
                            gsap.set(panel, { zIndex: panels.length - index });
                        },
                    });
                });

                // Pin the last panel
                const lastPanel = panels[panels.length - 1];
                ScrollTrigger.create({
                    trigger: lastPanel,
                    start: 'top top',
                    end: 'bottom bottom',
                    pin: lastPanel,
                    pinSpacing: false,
                });

            }, container);

            // Store context for cleanup
            container._gsapContext = ctx;
        }

        createAnimation(currentPanel, nextPanel, settings, index) {
            const tl = gsap.timeline();

            switch (settings.effectType) {
                case 'stack':
                    // Stacking cards effect - current panel scales down and darkens
                    tl.to(currentPanel, {
                        scale: settings.scaleAmount,
                        y: -settings.stackOffset,
                        filter: 'brightness(0.8)',
                        ease: 'none'
                    }, 0);
                    // Next panel fades in from black to color
                    tl.to(nextPanel, {
                        filter: 'brightness(1)',
                        ease: 'none'
                    }, 0);
                    break;

                case 'slide':
                    // Slide over effect
                    tl.to(currentPanel, {
                        y: '-100%',
                        ease: 'none'
                    }, 0);
                    tl.to(nextPanel, {
                        filter: 'brightness(1)',
                        ease: 'none'
                    }, 0);
                    break;

                case 'fade-scale':
                    // Fade and scale effect
                    tl.to(currentPanel, {
                        scale: settings.scaleAmount,
                        opacity: 0.5,
                        filter: 'blur(10px)',
                        ease: 'none'
                    }, 0);
                    tl.to(nextPanel, {
                        filter: 'brightness(1)',
                        ease: 'none'
                    }, 0);
                    break;

                case 'rotate':
                    // Rotate out effect
                    tl.to(currentPanel, {
                        rotationX: -10,
                        scale: 0.95,
                        transformOrigin: 'center top',
                        filter: 'brightness(0.7)',
                        ease: 'none'
                    }, 0);
                    tl.to(nextPanel, {
                        filter: 'brightness(1)',
                        ease: 'none'
                    }, 0);
                    break;

                default:
                    tl.to(currentPanel, {
                        scale: settings.scaleAmount,
                        y: -settings.stackOffset,
                        ease: 'none'
                    }, 0);
                    tl.to(nextPanel, {
                        filter: 'brightness(1)',
                        ease: 'none'
                    }, 0);
            }

            return tl;
        }

        setupResizeHandler(container, $panels, settings) {
            let resizeTimer;
            const handleResize = () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    // Kill existing context
                    if (container._gsapContext) {
                        container._gsapContext.kill();
                    }

                    // Kill all ScrollTriggers associated with this container
                    ScrollTrigger.getAll().forEach(st => {
                        if (st.trigger && container.contains(st.trigger)) {
                            st.kill();
                        }
                    });

                    // Refresh ScrollTrigger
                    ScrollTrigger.refresh();

                    // Reinitialize
                    this.initScrollytelling(container, $panels, settings);
                }, 300);
            };

            window.addEventListener('resize', handleResize);

            // Store handler for cleanup
            container._resizeHandler = handleResize;
        }

        onDestroy() {
            const $container = this.elements.$container[0];

            if ($container) {
                // Kill GSAP context
                if ($container._gsapContext) {
                    $container._gsapContext.kill();
                }

                // Remove resize handler
                if ($container._resizeHandler) {
                    window.removeEventListener('resize', $container._resizeHandler);
                }

                // Kill associated ScrollTriggers
                ScrollTrigger.getAll().forEach(st => {
                    if (st.trigger && $container.contains(st.trigger)) {
                        st.kill();
                    }
                });
            }
        }
    }

    // Register the handler
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/egw-scrollytelling-pin.default',
            ($element) => {
                elementorFrontend.elementsHandler.addHandler(ScrollytellingPinHandler, {
                    $element,
                });
            }
        );
    });

})(jQuery);
