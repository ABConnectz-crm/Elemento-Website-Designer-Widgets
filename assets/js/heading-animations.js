/**
 * Comprehensive Heading Animations Handler
 * Handles all 20 heading animation types using FREE GSAP plugins
 */
(function($) {
    'use strict';

    window.EGW = window.EGW || {};

    /**
     * Heading Animations Manager
     */
    window.EGW.HeadingAnimations = {

        /**
         * Initialize all heading animations
         */
        init: function() {
            const headings = document.querySelectorAll('.egw-animated-heading:not(.egw-heading-initialized)');

            headings.forEach(heading => {
                this.initHeading(heading);
                heading.classList.add('egw-heading-initialized');
            });
        },

        /**
         * Initialize single heading
         */
        initHeading: function(element) {
            const animationType = element.getAttribute('data-egw-heading-animation');

            if (!animationType || typeof gsap === 'undefined') {
                return;
            }

            // Get common settings
            const settings = this.getSettings(element);

            // Route to appropriate animation
            switch (animationType) {
                // Professional Corporate Animations
                case 'fade-in-stagger':
                    this.fadeInStagger(element, settings);
                    break;
                case 'slide-up':
                    this.slideUpReveal(element, settings);
                    break;
                case 'letter-expand':
                    this.letterSpacingExpand(element, settings);
                    break;
                case 'blur-focus':
                    this.blurToFocus(element, settings);
                    break;
                case 'scale-pulse':
                    this.scalePulse(element, settings);
                    break;
                case 'split-color':
                    this.splitColorReveal(element, settings);
                    break;
                case 'underline-draw':
                    this.underlineDraw(element, settings);
                    break;
                case 'glow-pulse':
                    this.glowPulse(element, settings);
                    break;
                case 'word-rotate':
                    this.wordRotateIn(element, settings);
                    break;
                case 'minimal-fade':
                    this.minimalFadeSlide(element, settings);
                    break;

                // Funky/Creative Animations
                case 'elastic-bounce':
                    this.elasticBounce(element, settings);
                    break;
                case 'wave-motion':
                    this.waveMotion(element, settings);
                    break;
                case 'scramble':
                    this.scrambleText(element, settings);
                    break;
                case 'neon-flicker':
                    this.neonFlicker(element, settings);
                    break;
                case 'glitch':
                    this.glitchReveal(element, settings);
                    break;
                case 'typewriter':
                    this.typewriterCursor(element, settings);
                    break;
                case 'scatter':
                    this.randomScatter(element, settings);
                    break;
                case 'flip-cards':
                    this.flipCards(element, settings);
                    break;
                case 'magnetic':
                    this.magneticPull(element, settings);
                    break;
                case 'liquid-morph':
                    this.liquidMorph(element, settings);
                    break;
            }

            // Store settings for responsive handling
            element._egwSettings = settings;
            this.setupResizeHandler(element, animationType);
        },

        /**
         * Get common settings from data attributes
         */
        getSettings: function(element) {
            return {
                duration: parseFloat(element.getAttribute('data-egw-duration')) || 1,
                delay: parseFloat(element.getAttribute('data-egw-delay')) || 0,
                easing: element.getAttribute('data-egw-easing') || 'power2.out',
                scrollTrigger: element.getAttribute('data-egw-scrolltrigger') === 'true',
                triggerStart: element.getAttribute('data-egw-trigger-start') || 'top 80%',
                scrub: element.getAttribute('data-egw-scrub'),
                markers: element.getAttribute('data-egw-markers') === 'true',
            };
        },

        /**
         * Create ScrollTrigger config
         */
        getScrollTriggerConfig: function(element, settings) {
            const config = {
                trigger: element,
                start: settings.triggerStart,
                toggleActions: 'play none none none',
                markers: settings.markers,
            };

            if (settings.scrub) {
                config.scrub = parseFloat(settings.scrub);
            }

            return config;
        },

        /**
         * Split text utility
         */
        splitText: function(element, type = 'words') {
            if (typeof window.EGWTextSplitter === 'undefined') {
                console.warn('EGWTextSplitter not loaded');
                return null;
            }

            return new window.EGWTextSplitter(element, {
                type: type
            });
        },

        // ============================================================
        // PROFESSIONAL CORPORATE ANIMATIONS
        // ============================================================

        /**
         * Fade In Stagger
         */
        fadeInStagger: function(element, settings) {
            const splitType = element.getAttribute('data-egw-split-type') || 'words';
            const stagger = parseFloat(element.getAttribute('data-egw-stagger')) || 0.05;
            const staggerFrom = element.getAttribute('data-egw-stagger-from') || 'start';

            const splitter = this.splitText(element, splitType);
            if (!splitter) return;

            let targets;
            switch (splitType) {
                case 'chars': targets = splitter.chars; break;
                case 'lines': targets = splitter.lines; break;
                default: targets = splitter.words;
            }

            const animConfig = {
                opacity: 1,
                duration: settings.duration,
                delay: settings.delay,
                ease: settings.easing,
                stagger: {
                    amount: stagger * targets.length,
                    from: staggerFrom
                }
            };

            gsap.set(targets, { opacity: 0 });

            if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                animConfig.scrollTrigger = this.getScrollTriggerConfig(element, settings);
            }

            gsap.to(targets, animConfig);
        },

        /**
         * Slide Up Reveal
         */
        slideUpReveal: function(element, settings) {
            const splitType = element.getAttribute('data-egw-split-type') || 'lines';
            const distance = parseFloat(element.getAttribute('data-egw-slide-distance')) || 50;
            const stagger = parseFloat(element.getAttribute('data-egw-stagger')) || 0.1;
            const useClip = element.getAttribute('data-egw-use-clip') === 'true';

            const splitter = this.splitText(element, splitType);
            if (!splitter) return;

            const targets = splitType === 'lines' ? splitter.lines : splitter.words;

            if (useClip) {
                targets.forEach(target => {
                    const wrapper = document.createElement('div');
                    wrapper.style.cssText = 'overflow: hidden; display: block;';
                    target.parentNode.insertBefore(wrapper, target);
                    wrapper.appendChild(target);
                });
            }

            const animConfig = {
                y: 0,
                opacity: 1,
                duration: settings.duration,
                ease: settings.easing,
                stagger: stagger
            };

            gsap.set(targets, { y: distance, opacity: 0 });

            if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                animConfig.scrollTrigger = this.getScrollTriggerConfig(element, settings);
            }

            gsap.to(targets, animConfig);
        },

        /**
         * Letter Spacing Expand
         */
        letterSpacingExpand: function(element, settings) {
            const initialSpacing = parseFloat(element.getAttribute('data-egw-initial-spacing')) || 0.5;
            const finalSpacing = parseFloat(element.getAttribute('data-egw-final-spacing')) || 0;

            const animConfig = {
                letterSpacing: `${finalSpacing}em`,
                opacity: 1,
                duration: settings.duration,
                delay: settings.delay,
                ease: settings.easing
            };

            gsap.set(element, { letterSpacing: `${initialSpacing}em`, opacity: 0 });

            if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                animConfig.scrollTrigger = this.getScrollTriggerConfig(element, settings);
            }

            gsap.to(element, animConfig);
        },

        /**
         * Blur to Focus
         */
        blurToFocus: function(element, settings) {
            const blurAmount = parseFloat(element.getAttribute('data-egw-blur-amount')) || 20;
            const splitType = element.getAttribute('data-egw-split-type') || 'words';
            const stagger = parseFloat(element.getAttribute('data-egw-stagger')) || 0.05;

            if (splitType === 'none') {
                const animConfig = {
                    filter: 'blur(0px)',
                    opacity: 1,
                    duration: settings.duration,
                    delay: settings.delay,
                    ease: settings.easing
                };

                gsap.set(element, { filter: `blur(${blurAmount}px)`, opacity: 0 });

                if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                    animConfig.scrollTrigger = this.getScrollTriggerConfig(element, settings);
                }

                gsap.to(element, animConfig);
            } else {
                const splitter = this.splitText(element, splitType);
                if (!splitter) return;

                const targets = splitType === 'chars' ? splitter.chars : splitter.words;

                const animConfig = {
                    filter: 'blur(0px)',
                    opacity: 1,
                    duration: settings.duration,
                    ease: settings.easing,
                    stagger: stagger
                };

                gsap.set(targets, { filter: `blur(${blurAmount}px)`, opacity: 0 });

                if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                    animConfig.scrollTrigger = this.getScrollTriggerConfig(element, settings);
                }

                gsap.to(targets, animConfig);
            }
        },

        /**
         * Scale Pulse
         */
        scalePulse: function(element, settings) {
            const scaleFrom = parseFloat(element.getAttribute('data-egw-scale-from')) || 0.8;
            const bounce = element.getAttribute('data-egw-bounce') || 'back.out(1.4)';

            const animConfig = {
                scale: 1,
                opacity: 1,
                duration: settings.duration,
                delay: settings.delay,
                ease: bounce
            };

            gsap.set(element, { scale: scaleFrom, opacity: 0 });

            if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                animConfig.scrollTrigger = this.getScrollTriggerConfig(element, settings);
            }

            gsap.to(element, animConfig);
        },

        /**
         * Split Color Reveal
         */
        splitColorReveal: function(element, settings) {
            const initialColor = element.getAttribute('data-egw-initial-color') || '#cccccc';
            const finalColor = window.getComputedStyle(element).color;
            const revealFrom = element.getAttribute('data-egw-reveal-from') || 'left';

            const splitter = this.splitText(element, 'chars');
            if (!splitter) return;

            const chars = splitter.chars;

            const animConfig = {
                color: finalColor,
                duration: settings.duration,
                ease: settings.easing,
                stagger: {
                    amount: 0.5,
                    from: revealFrom
                }
            };

            gsap.set(chars, { color: initialColor });

            if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                animConfig.scrollTrigger = this.getScrollTriggerConfig(element, settings);
            }

            gsap.to(chars, animConfig);
        },

        /**
         * Underline Draw
         */
        underlineDraw: function(element, settings) {
            const height = parseFloat(element.getAttribute('data-egw-underline-height')) || 3;
            const color = element.getAttribute('data-egw-underline-color') || '#000000';
            const direction = element.getAttribute('data-egw-draw-direction') || 'left-to-right';

            // Create underline element
            const underline = document.createElement('div');
            underline.style.cssText = `
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
                height: ${height}px;
                background: ${color};
                transform-origin: ${direction === 'right-to-left' ? 'right' : 'left'};
            `;

            element.style.position = 'relative';
            element.appendChild(underline);

            const animConfig = {
                scaleX: 1,
                duration: settings.duration,
                delay: settings.delay,
                ease: settings.easing
            };

            gsap.set(underline, { scaleX: 0 });

            if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                animConfig.scrollTrigger = this.getScrollTriggerConfig(element, settings);
            }

            gsap.to(underline, animConfig);

            // Fade in text
            gsap.from(element, {
                opacity: 0,
                duration: settings.duration * 0.5,
                delay: settings.delay
            });
        },

        /**
         * Glow Pulse
         */
        glowPulse: function(element, settings) {
            const glowColor = element.getAttribute('data-egw-glow-color') || '#ffffff';
            const glowIntensity = parseFloat(element.getAttribute('data-egw-glow-intensity')) || 20;

            const animConfig = {
                textShadow: `0 0 ${glowIntensity}px ${glowColor}`,
                opacity: 1,
                duration: settings.duration,
                delay: settings.delay,
                ease: settings.easing
            };

            gsap.set(element, { textShadow: 'none', opacity: 0 });

            if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                animConfig.scrollTrigger = this.getScrollTriggerConfig(element, settings);
            }

            gsap.to(element, animConfig);
        },

        /**
         * Word Rotate In
         */
        wordRotateIn: function(element, settings) {
            const rotation = parseFloat(element.getAttribute('data-egw-rotation')) || 90;
            const stagger = parseFloat(element.getAttribute('data-egw-stagger')) || 0.08;

            const splitter = this.splitText(element, 'words');
            if (!splitter) return;

            const animConfig = {
                rotationX: 0,
                opacity: 1,
                y: 0,
                duration: settings.duration,
                ease: settings.easing,
                stagger: stagger
            };

            gsap.set(splitter.words, {
                rotationX: rotation,
                opacity: 0,
                y: 20,
                transformOrigin: '50% 50% -50px'
            });

            if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                animConfig.scrollTrigger = this.getScrollTriggerConfig(element, settings);
            }

            gsap.to(splitter.words, animConfig);
        },

        /**
         * Minimal Fade Slide
         */
        minimalFadeSlide: function(element, settings) {
            const distance = parseFloat(element.getAttribute('data-egw-slide-distance')) || 20;
            const direction = element.getAttribute('data-egw-slide-direction') || 'up';

            let fromVars = { opacity: 0 };
            switch (direction) {
                case 'up': fromVars.y = distance; break;
                case 'down': fromVars.y = -distance; break;
                case 'left': fromVars.x = distance; break;
                case 'right': fromVars.x = -distance; break;
            }

            const animConfig = {
                ...fromVars,
                y: 0,
                x: 0,
                opacity: 1,
                duration: settings.duration,
                delay: settings.delay,
                ease: settings.easing
            };

            gsap.set(element, fromVars);

            if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                animConfig.scrollTrigger = this.getScrollTriggerConfig(element, settings);
            }

            gsap.to(element, animConfig);
        },

        // ============================================================
        // FUNKY/CREATIVE ANIMATIONS
        // ============================================================

        /**
         * Elastic Bounce
         */
        elasticBounce: function(element, settings) {
            const elastic = element.getAttribute('data-egw-elastic') || 'elastic.out(1, 0.5)';
            const scaleFrom = parseFloat(element.getAttribute('data-egw-scale-from')) || 0;
            const stagger = parseFloat(element.getAttribute('data-egw-stagger')) || 0.05;

            const splitter = this.splitText(element, 'chars');
            if (!splitter) return;

            const animConfig = {
                scale: 1,
                opacity: 1,
                y: 0,
                duration: settings.duration,
                ease: elastic,
                stagger: stagger
            };

            gsap.set(splitter.chars, { scale: scaleFrom, opacity: 0, y: -50 });

            if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                animConfig.scrollTrigger = this.getScrollTriggerConfig(element, settings);
            }

            gsap.to(splitter.chars, animConfig);
        },

        /**
         * Wave Motion
         */
        waveMotion: function(element, settings) {
            const waveHeight = parseFloat(element.getAttribute('data-egw-wave-height')) || 30;
            const waveDuration = parseFloat(element.getAttribute('data-egw-wave-duration')) || 1.5;
            const stagger = parseFloat(element.getAttribute('data-egw-stagger')) || 0.03;

            const splitter = this.splitText(element, 'chars');
            if (!splitter) return;

            splitter.chars.forEach((char, index) => {
                const tl = gsap.timeline({ repeat: -1, yoyo: true });

                tl.to(char, {
                    y: -waveHeight,
                    duration: waveDuration,
                    ease: 'sine.inOut',
                    delay: index * stagger
                });

                if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                    ScrollTrigger.create({
                        trigger: element,
                        start: settings.triggerStart,
                        onEnter: () => tl.play(),
                        onLeave: () => tl.pause(),
                        onEnterBack: () => tl.play(),
                        onLeaveBack: () => tl.pause(),
                    });
                }
            });
        },

        /**
         * Scramble Text
         */
        scrambleText: function(element, settings) {
            const duration = parseFloat(element.getAttribute('data-egw-scramble-duration')) || 1;
            const charset = element.getAttribute('data-egw-charset') || 'alphanumeric';

            const originalText = element.textContent;
            const chars = {
                alphanumeric: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
                letters: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                numbers: '0123456789',
                symbols: '!@#$%^&*()_+-=[]{}|;:,.<>?'
            };

            const charSet = chars[charset] || chars.alphanumeric;

            const animate = () => {
                let iteration = 0;
                const interval = setInterval(() => {
                    element.textContent = originalText
                        .split('')
                        .map((char, index) => {
                            if (index < iteration) {
                                return originalText[index];
                            }
                            return charSet[Math.floor(Math.random() * charSet.length)];
                        })
                        .join('');

                    if (iteration >= originalText.length) {
                        clearInterval(interval);
                    }

                    iteration += 1 / 3;
                }, (duration * 1000) / (originalText.length * 3));
            };

            if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                ScrollTrigger.create({
                    trigger: element,
                    start: settings.triggerStart,
                    onEnter: animate,
                });
            } else {
                setTimeout(animate, settings.delay * 1000);
            }
        },

        /**
         * Neon Flicker
         */
        neonFlicker: function(element, settings) {
            const neonColor = element.getAttribute('data-egw-neon-color') || '#00ff00';
            const glowIntensity = parseFloat(element.getAttribute('data-egw-glow-intensity')) || 20;
            const flickerSpeed = element.getAttribute('data-egw-flicker-speed') || 'medium';

            const speeds = { slow: 0.3, medium: 0.15, fast: 0.08 };
            const speed = speeds[flickerSpeed] || speeds.medium;

            const tl = gsap.timeline({ repeat: 3 });

            tl.to(element, {
                textShadow: `0 0 ${glowIntensity}px ${neonColor}, 0 0 ${glowIntensity * 2}px ${neonColor}`,
                duration: speed,
                ease: 'none'
            })
            .to(element, {
                textShadow: 'none',
                duration: speed * 0.5,
                ease: 'none'
            })
            .to(element, {
                textShadow: `0 0 ${glowIntensity}px ${neonColor}`,
                duration: speed,
                ease: 'none'
            });

            if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                ScrollTrigger.create({
                    trigger: element,
                    start: settings.triggerStart,
                    onEnter: () => tl.restart(),
                });
            } else {
                gsap.delayedCall(settings.delay, () => tl.play());
            }
        },

        /**
         * Glitch Reveal
         */
        glitchReveal: function(element, settings) {
            const intensity = parseFloat(element.getAttribute('data-egw-glitch-intensity')) || 10;
            const layers = parseInt(element.getAttribute('data-egw-glitch-layers')) || 2;
            const colorShift = element.getAttribute('data-egw-color-shift') === 'true';

            // Create glitch effect
            for (let i = 0; i < layers; i++) {
                const clone = element.cloneNode(true);
                clone.style.position = 'absolute';
                clone.style.top = '0';
                clone.style.left = '0';
                clone.style.opacity = '0.8';

                if (colorShift) {
                    clone.style.color = i === 0 ? 'cyan' : 'magenta';
                    clone.style.mixBlendMode = 'multiply';
                }

                element.parentNode.insertBefore(clone, element);

                gsap.to(clone, {
                    x: () => gsap.utils.random(-intensity, intensity),
                    y: () => gsap.utils.random(-intensity / 2, intensity / 2),
                    duration: 0.1,
                    repeat: 8,
                    yoyo: true,
                    ease: 'none',
                    onComplete: () => {
                        clone.style.opacity = '0';
                    }
                });
            }

            // Fade in original
            gsap.from(element, {
                opacity: 0,
                duration: settings.duration,
                delay: settings.delay,
                ease: settings.easing
            });
        },

        /**
         * Typewriter Cursor
         */
        typewriterCursor: function(element, settings) {
            const typingSpeed = parseFloat(element.getAttribute('data-egw-typing-speed')) || 0.1;
            const cursorStyle = element.getAttribute('data-egw-cursor-style') || 'block';
            const cursorBlink = element.getAttribute('data-egw-cursor-blink') === 'true';

            const originalText = element.textContent;
            element.textContent = '';

            // Create cursor
            const cursor = document.createElement('span');
            cursor.className = 'egw-typewriter-cursor';
            cursor.style.cssText = `
                display: inline-block;
                width: ${cursorStyle === 'block' ? '0.6em' : '2px'};
                height: ${cursorStyle === 'underscore' ? '2px' : '1em'};
                background: currentColor;
                margin-left: 2px;
                ${cursorBlink ? 'animation: egw-cursor-blink 1s infinite;' : ''}
            `;
            element.appendChild(cursor);

            let index = 0;
            const typeChar = () => {
                if (index < originalText.length) {
                    element.insertBefore(
                        document.createTextNode(originalText.charAt(index)),
                        cursor
                    );
                    index++;
                    gsap.delayedCall(typingSpeed, typeChar);
                }
            };

            if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                ScrollTrigger.create({
                    trigger: element,
                    start: settings.triggerStart,
                    onEnter: typeChar,
                });
            } else {
                gsap.delayedCall(settings.delay, typeChar);
            }
        },

        /**
         * Random Scatter
         */
        randomScatter: function(element, settings) {
            const distance = parseFloat(element.getAttribute('data-egw-scatter-distance')) || 200;
            const rotationRange = parseFloat(element.getAttribute('data-egw-rotation-range')) || 180;
            const stagger = parseFloat(element.getAttribute('data-egw-stagger')) || 0.05;

            const splitter = this.splitText(element, 'chars');
            if (!splitter) return;

            splitter.chars.forEach(char => {
                const randomX = gsap.utils.random(-distance, distance);
                const randomY = gsap.utils.random(-distance, distance);
                const randomRotation = gsap.utils.random(-rotationRange, rotationRange);

                gsap.set(char, {
                    x: randomX,
                    y: randomY,
                    rotation: randomRotation,
                    opacity: 0
                });
            });

            const animConfig = {
                x: 0,
                y: 0,
                rotation: 0,
                opacity: 1,
                duration: settings.duration,
                ease: settings.easing,
                stagger: {
                    amount: stagger * splitter.chars.length,
                    from: 'random'
                }
            };

            if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                animConfig.scrollTrigger = this.getScrollTriggerConfig(element, settings);
            }

            gsap.to(splitter.chars, animConfig);
        },

        /**
         * Flip Cards
         */
        flipCards: function(element, settings) {
            const flipAxis = element.getAttribute('data-egw-flip-axis') || 'y';
            const backColor = element.getAttribute('data-egw-back-color') || '#ff0000';
            const stagger = parseFloat(element.getAttribute('data-egw-stagger')) || 0.05;

            const splitter = this.splitText(element, 'chars');
            if (!splitter) return;

            splitter.chars.forEach(char => {
                char.style.transformStyle = 'preserve-3d';
                char.style.backfaceVisibility = 'hidden';

                // Create back side
                const back = char.cloneNode(true);
                back.style.position = 'absolute';
                back.style.top = '0';
                back.style.left = '0';
                back.style.color = backColor;
                back.style.transform = flipAxis === 'y' ? 'rotateY(180deg)' : 'rotateX(180deg)';
                char.parentNode.insertBefore(back, char.nextSibling);
            });

            const rotationProp = flipAxis === 'y' ? 'rotationY' : 'rotationX';

            gsap.set(splitter.chars, { [rotationProp]: -180 });

            const animConfig = {
                [rotationProp]: 0,
                duration: settings.duration,
                ease: 'power2.inOut',
                stagger: stagger
            };

            if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                animConfig.scrollTrigger = this.getScrollTriggerConfig(element, settings);
            }

            gsap.to(splitter.chars, animConfig);
        },

        /**
         * Magnetic Pull
         */
        magneticPull: function(element, settings) {
            const pullOrigin = element.getAttribute('data-egw-pull-origin') || 'center';
            const strength = parseFloat(element.getAttribute('data-egw-magnetic-strength')) || 1;
            const stagger = parseFloat(element.getAttribute('data-egw-stagger')) || 0.05;

            const splitter = this.splitText(element, 'chars');
            if (!splitter) return;

            const origins = {
                center: { x: 0, y: 0 },
                left: { x: -200 * strength, y: 0 },
                right: { x: 200 * strength, y: 0 },
                top: { x: 0, y: -200 * strength },
                bottom: { x: 0, y: 200 * strength }
            };

            const origin = origins[pullOrigin] || origins.center;

            gsap.set(splitter.chars, {
                x: origin.x,
                y: origin.y,
                scale: 0,
                opacity: 0
            });

            const animConfig = {
                x: 0,
                y: 0,
                scale: 1,
                opacity: 1,
                duration: settings.duration,
                ease: 'back.out(1.7)',
                stagger: stagger
            };

            if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                animConfig.scrollTrigger = this.getScrollTriggerConfig(element, settings);
            }

            gsap.to(splitter.chars, animConfig);
        },

        /**
         * Liquid Morph
         */
        liquidMorph: function(element, settings) {
            const intensity = parseFloat(element.getAttribute('data-egw-morph-intensity')) || 2;
            const waveCount = parseInt(element.getAttribute('data-egw-wave-count')) || 3;
            const speed = parseFloat(element.getAttribute('data-egw-liquid-speed')) || 2;

            const splitter = this.splitText(element, 'chars');
            if (!splitter) return;

            splitter.chars.forEach((char, index) => {
                const tl = gsap.timeline({ repeat: waveCount - 1 });

                tl.to(char, {
                    scaleY: 1 + (intensity / 10),
                    scaleX: 1 - (intensity / 20),
                    y: -10,
                    duration: speed / 2,
                    ease: 'sine.inOut'
                })
                .to(char, {
                    scaleY: 1 - (intensity / 20),
                    scaleX: 1 + (intensity / 10),
                    y: 10,
                    duration: speed / 2,
                    ease: 'sine.inOut'
                })
                .to(char, {
                    scaleY: 1,
                    scaleX: 1,
                    y: 0,
                    duration: speed / 2,
                    ease: 'sine.inOut'
                });

                if (settings.scrollTrigger && typeof ScrollTrigger !== 'undefined') {
                    ScrollTrigger.create({
                        trigger: element,
                        start: settings.triggerStart,
                        onEnter: () => gsap.delayedCall(index * 0.03, () => tl.restart()),
                    });
                } else {
                    gsap.delayedCall(settings.delay + (index * 0.03), () => tl.play());
                }
            });
        },

        /**
         * Setup resize handler for responsive behavior
         */
        setupResizeHandler: function(element, animationType) {
            let resizeTimer;
            const handleResize = () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    // Revert text split if exists
                    if (element._textSplitter) {
                        element._textSplitter.revert();
                    }

                    // Kill existing animations
                    gsap.killTweensOf(element);
                    gsap.killTweensOf(element.querySelectorAll('*'));

                    // Reinitialize
                    const settings = element._egwSettings;
                    if (settings) {
                        element.classList.remove('egw-heading-initialized');
                        this.initHeading(element);
                    }
                }, 250);
            };

            window.addEventListener('resize', handleResize);
        }
    };

    // Initialize on DOM ready
    $(document).ready(function() {
        if (typeof gsap !== 'undefined') {
            window.EGW.HeadingAnimations.init();
        }
    });

    // Reinitialize on Elementor preview
    if (typeof elementorFrontend !== 'undefined') {
        $(window).on('elementor/frontend/init', function() {
            elementorFrontend.hooks.addAction('frontend/element_ready/global', function() {
                setTimeout(function() {
                    window.EGW.HeadingAnimations.init();
                }, 100);
            });
        });
    }

    // Add cursor blink animation to head
    if (!document.getElementById('egw-typewriter-styles')) {
        const style = document.createElement('style');
        style.id = 'egw-typewriter-styles';
        style.textContent = `
            @keyframes egw-cursor-blink {
                0%, 49% { opacity: 1; }
                50%, 100% { opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }

})(jQuery);
