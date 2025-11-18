/**
 * Elementor Editor JavaScript
 */
(function($) {
    'use strict';

    /**
     * Initialize editor functionality
     */
    $(window).on('elementor:init', function() {

        /**
         * Preview animations in editor
         */
        elementor.hooks.addAction('panel/open_editor/widget', function(panel, model, view) {
            const widgetType = model.get('widgetType');

            // Check if this is an EGW widget
            if (widgetType && widgetType.startsWith('egw-')) {
                // Add live preview for animations
                enableLivePreview(panel, model, view);
            }
        });

        /**
         * Enable live preview for animations
         */
        function enableLivePreview(panel, model, view) {
            // Listen for setting changes
            model.on('change', function() {
                // Debounce the preview update
                clearTimeout(window.egwPreviewTimeout);
                window.egwPreviewTimeout = setTimeout(function() {
                    refreshAnimations();
                }, 500);
            });
        }

        /**
         * Refresh animations in preview
         */
        function refreshAnimations() {
            if (typeof window.EGW !== 'undefined' && window.EGW.AnimationUtils) {
                // Kill existing animations
                window.EGW.AnimationUtils.killAll();

                // Re-initialize
                setTimeout(function() {
                    window.EGW.AnimationUtils.refresh();
                }, 100);
            }
        }

        /**
         * Add custom controls enhancement
         */
        elementor.hooks.addAction('panel/open_editor/widget/egw-staggered-text', function(panel, model, view) {
            addAnimationPreview(panel);
        });

        /**
         * Add animation preview button
         */
        function addAnimationPreview(panel) {
            // Add a preview button to test animations
            const $previewBtn = $('<button class="elementor-button egw-preview-animation" style="margin: 10px 0;">')
                .text('Preview Animation')
                .on('click', function(e) {
                    e.preventDefault();
                    refreshAnimations();
                });

            // Append to appropriate section
            setTimeout(function() {
                const $animSection = panel.$el.find('[data-setting="section_gsap_animation"]');
                if ($animSection.length) {
                    $animSection.find('.elementor-panel-heading').after($previewBtn);
                }
            }, 100);
        }

        /**
         * Handle widget deletion - cleanup animations
         */
        elementor.channels.editor.on('elementor:destroy', function() {
            if (typeof window.EGW !== 'undefined' && window.EGW.AnimationUtils) {
                window.EGW.AnimationUtils.killAll();
            }
        });

    });

    /**
     * Preview mode handlers
     */
    $(window).on('elementor/frontend/init', function() {

        // Reinitialize on preview mode
        elementorFrontend.hooks.addAction('frontend/element_ready/global', function($scope) {
            // Check if scope contains EGW widgets
            const $egwWidgets = $scope.find('[data-egw-animation]');

            if ($egwWidgets.length > 0) {
                // Remove initialized classes to allow re-initialization
                $egwWidgets.removeClass('egw-animated egw-stagger-initialized egw-split-initialized');

                // Reinitialize animations after a short delay
                setTimeout(function() {
                    if (typeof window.EGW !== 'undefined' && window.EGW.AnimationUtils) {
                        window.EGW.AnimationUtils.refresh();
                    }
                }, 100);
            }
        });

        // Special handling for specific widget types
        elementorFrontend.hooks.addAction('frontend/element_ready/egw-staggered-text.default', function($scope) {
            initStaggeredText($scope);
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/egw-parallax-image.default', function($scope) {
            initParallaxImage($scope);
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/egw-flip-box.default', function($scope) {
            initFlipBox($scope);
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/egw-marquee.default', function($scope) {
            initMarquee($scope);
        });

    });

    /**
     * Initialize staggered text in preview
     */
    function initStaggeredText($scope) {
        const $element = $scope.find('[data-egw-stagger]');
        if ($element.length && typeof window.EGW !== 'undefined') {
            window.EGW.AnimationUtils.animateStaggeredText($element[0]);
        }
    }

    /**
     * Initialize parallax image in preview
     */
    function initParallaxImage($scope) {
        const $element = $scope.find('[data-egw-parallax]');
        if ($element.length && typeof window.EGW !== 'undefined') {
            window.EGW.AnimationUtils.animateParallax($element[0]);
        }
    }

    /**
     * Initialize flip box in preview
     */
    function initFlipBox($scope) {
        const $element = $scope.find('[data-egw-flip-box]');
        if ($element.length && typeof window.EGW !== 'undefined') {
            window.EGW.AnimationUtils.initFlipBox($element[0]);
        }
    }

    /**
     * Initialize marquee in preview
     */
    function initMarquee($scope) {
        const $element = $scope.find('[data-egw-marquee]');
        if ($element.length) {
            // Marquee initialization will be handled by the widget's own script
            console.log('Marquee initialized in preview');
        }
    }

})(jQuery);
