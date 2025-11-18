/**
 * Kinetic 3D Cylinder Text Handler
 * Creates Awwwards-style 3D text animations using only FREE GSAP plugins
 */
(function($) {
    'use strict';

    class Kinetic3DHandler extends elementorModules.frontend.handlers.Base {
        getDefaultSettings() {
            return {
                selectors: {
                    container: '.egw-kinetic-3d-container',
                    text: '.egw-kinetic-3d-text',
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
                type: $text.data('egw-3d-type') || 'kinetic-cylinder',
                animation: $text.data('egw-animation') || 'scroll-rotate',
                radius: parseFloat($text.data('egw-cylinder-radius')) || 200,
                axis: $text.data('egw-rotation-axis') || 'y',
                charSpacing: parseFloat($text.data('egw-char-spacing')) || 8,
                depthFade: $text.data('egw-depth-fade') === 'true',
                depthBlur: $text.data('egw-depth-blur') === 'true',
                speed: parseFloat($text.data('egw-rotation-speed')) || 1,
                direction: $text.data('egw-rotation-direction') || 'clockwise',
                initialRotation: parseFloat($text.data('egw-initial-rotation')) || 0,
                scrollTrigger: $text.data('egw-scrolltrigger') === 'true',
                triggerStart: $text.data('egw-trigger-start') || 'top bottom',
                triggerEnd: $text.data('egw-trigger-end') || 'bottom top',
                scrub: $text.data('egw-scrub'),
            };

            // Use EGW Text Splitter (our free alternative)
            if (typeof window.EGWTextSplitter !== 'undefined') {
                this.initCylinder($text[0], settings);
            } else {
                console.warn('EGWTextSplitter not loaded');
            }

            // Handle responsive resize
            this.setupResizeHandler($text[0], settings);
        }

        initCylinder(element, settings) {
            // Split text into characters
            const splitter = new window.EGWTextSplitter(element, {
                type: 'chars',
                charsClass: 'egw-3d-char'
            });

            const chars = splitter.chars;

            if (!chars.length) return;

            // Calculate positions for each character on the cylinder
            const totalChars = chars.length;
            const angleStep = settings.charSpacing;
            const radius = settings.radius;
            const axis = settings.axis;

            // Set up 3D context
            gsap.set(element, {
                transformStyle: 'preserve-3d',
                transformPerspective: 1000
            });

            // Position each character on the cylinder
            chars.forEach((char, index) => {
                const angle = (index * angleStep) + settings.initialRotation;
                const radians = (angle * Math.PI) / 180;

                let x = 0, y = 0, z = 0;
                let rotateX = 0, rotateY = 0;

                if (axis === 'y') {
                    // Vertical cylinder
                    x = Math.sin(radians) * radius;
                    z = Math.cos(radians) * radius;
                    rotateY = angle;
                } else {
                    // Horizontal cylinder
                    y = Math.sin(radians) * radius;
                    z = Math.cos(radians) * radius;
                    rotateX = angle;
                }

                // Calculate opacity based on Z depth
                const opacity = settings.depthFade ?
                    gsap.utils.mapRange(-radius, radius, 0.3, 1, z) : 1;

                // Calculate blur based on Z depth
                const blur = settings.depthBlur ?
                    gsap.utils.mapRange(-radius, radius, 5, 0, z) : 0;

                // Set initial position
                gsap.set(char, {
                    x: x,
                    y: y,
                    z: z,
                    rotationX: rotateX,
                    rotationY: rotateY,
                    opacity: opacity,
                    filter: `blur(${Math.max(0, blur)}px)`,
                    transformStyle: 'preserve-3d',
                    force3D: true
                });
            });

            // Apply animation based on type
            switch (settings.animation) {
                case 'scroll-rotate':
                    this.scrollRotate(element, chars, settings);
                    break;
                case 'auto-rotate':
                    this.autoRotate(element, chars, settings);
                    break;
                case 'hover-rotate':
                    this.hoverRotate(element, chars, settings);
                    break;
            }

            // Store splitter for cleanup
            element._splitter = splitter;
        }

        scrollRotate(element, chars, settings) {
            if (typeof ScrollTrigger === 'undefined') return;

            const direction = settings.direction === 'clockwise' ? 1 : -1;
            const rotationAmount = 360 * settings.speed * direction;
            const axis = settings.axis === 'y' ? 'rotationY' : 'rotationX';

            gsap.to(element, {
                [axis]: rotationAmount,
                ease: 'none',
                scrollTrigger: {
                    trigger: element,
                    start: settings.triggerStart,
                    end: settings.triggerEnd,
                    scrub: settings.scrub ? parseFloat(settings.scrub) : true,
                    onUpdate: (self) => {
                        // Update character opacity/blur based on rotation
                        if (settings.depthFade || settings.depthBlur) {
                            this.updateCharacterDepth(chars, settings);
                        }
                    }
                }
            });
        }

        autoRotate(element, chars, settings) {
            const direction = settings.direction === 'clockwise' ? 1 : -1;
            const axis = settings.axis === 'y' ? 'rotationY' : 'rotationX';
            const duration = 10 / settings.speed;

            gsap.to(element, {
                [axis]: `+=${360 * direction}`,
                duration: duration,
                ease: 'none',
                repeat: -1,
                onUpdate: () => {
                    if (settings.depthFade || settings.depthBlur) {
                        this.updateCharacterDepth(chars, settings);
                    }
                }
            });
        }

        hoverRotate(element, chars, settings) {
            const direction = settings.direction === 'clockwise' ? 1 : -1;
            const axis = settings.axis === 'y' ? 'rotationY' : 'rotationX';
            const rotationAmount = 180 * direction;

            element.addEventListener('mouseenter', () => {
                gsap.to(element, {
                    [axis]: `+=${rotationAmount}`,
                    duration: 1.5 / settings.speed,
                    ease: 'power2.out',
                    onUpdate: () => {
                        if (settings.depthFade || settings.depthBlur) {
                            this.updateCharacterDepth(chars, settings);
                        }
                    }
                });
            });
        }

        updateCharacterDepth(chars, settings) {
            chars.forEach(char => {
                const transform = window.getComputedStyle(char).transform;
                if (transform === 'none') return;

                // Extract Z value from transform matrix
                const matrix = new DOMMatrix(transform);
                const z = matrix.m43 || 0;

                if (settings.depthFade) {
                    const opacity = gsap.utils.mapRange(-settings.radius, settings.radius, 0.3, 1, z);
                    gsap.set(char, { opacity: opacity });
                }

                if (settings.depthBlur) {
                    const blur = gsap.utils.mapRange(-settings.radius, settings.radius, 5, 0, z);
                    gsap.set(char, { filter: `blur(${Math.max(0, blur)}px)` });
                }
            });
        }

        setupResizeHandler(element, settings) {
            let resizeTimer;
            const handleResize = () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    // Revert and reinitialize
                    if (element._splitter) {
                        element._splitter.revert();
                    }

                    // Kill existing animations
                    gsap.killTweensOf(element);
                    if (typeof ScrollTrigger !== 'undefined') {
                        ScrollTrigger.getAll().forEach(st => {
                            if (st.trigger === element) st.kill();
                        });
                    }

                    // Reinitialize
                    this.initCylinder(element, settings);
                }, 250);
            };

            window.addEventListener('resize', handleResize);

            // Cleanup on destroy
            element._resizeHandler = handleResize;
        }

        onDestroy() {
            const $text = this.elements.$text[0];

            if ($text) {
                // Revert text split
                if ($text._splitter) {
                    $text._splitter.revert();
                }

                // Remove resize handler
                if ($text._resizeHandler) {
                    window.removeEventListener('resize', $text._resizeHandler);
                }

                // Kill GSAP animations
                gsap.killTweensOf($text);
            }
        }
    }

    // Register the handler
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/egw-kinetic-3d-text.default',
            ($element) => {
                elementorFrontend.elementsHandler.addHandler(Kinetic3DHandler, {
                    $element,
                });
            }
        );
    });

})(jQuery);
