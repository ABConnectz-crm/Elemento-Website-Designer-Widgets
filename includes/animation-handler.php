<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Animation Handler Class
 *
 * Handles common animation logic and utilities
 */
class Animation_Handler {

    /**
     * Initialize
     */
    public static function init() {
        add_action('wp_footer', [__CLASS__, 'add_reduced_motion_css']);
    }

    /**
     * Get default animation settings
     */
    public static function get_default_settings() {
        return [
            'duration' => 1,
            'delay' => 0,
            'easing' => 'power2.out',
            'stagger' => 0.05,
            'trigger_start' => 'top 80%',
            'trigger_end' => 'bottom 20%',
        ];
    }

    /**
     * Sanitize animation settings
     */
    public static function sanitize_settings($settings) {
        $defaults = self::get_default_settings();

        return [
            'duration' => isset($settings['duration']) ? floatval($settings['duration']) : $defaults['duration'],
            'delay' => isset($settings['delay']) ? floatval($settings['delay']) : $defaults['delay'],
            'easing' => isset($settings['easing']) ? sanitize_text_field($settings['easing']) : $defaults['easing'],
            'stagger' => isset($settings['stagger']) ? floatval($settings['stagger']) : $defaults['stagger'],
            'trigger_start' => isset($settings['trigger_start']) ? sanitize_text_field($settings['trigger_start']) : $defaults['trigger_start'],
            'trigger_end' => isset($settings['trigger_end']) ? sanitize_text_field($settings['trigger_end']) : $defaults['trigger_end'],
        ];
    }

    /**
     * Get easing options
     */
    public static function get_easing_options() {
        return [
            'none' => 'Linear',
            'power1.in' => 'Power1 In',
            'power1.out' => 'Power1 Out',
            'power1.inOut' => 'Power1 InOut',
            'power2.in' => 'Power2 In',
            'power2.out' => 'Power2 Out',
            'power2.inOut' => 'Power2 InOut',
            'power3.in' => 'Power3 In',
            'power3.out' => 'Power3 Out',
            'power3.inOut' => 'Power3 InOut',
            'power4.in' => 'Power4 In',
            'power4.out' => 'Power4 Out',
            'power4.inOut' => 'Power4 InOut',
            'back.in' => 'Back In',
            'back.out' => 'Back Out',
            'back.inOut' => 'Back InOut',
            'elastic.in' => 'Elastic In',
            'elastic.out' => 'Elastic Out',
            'elastic.inOut' => 'Elastic InOut',
            'bounce.in' => 'Bounce In',
            'bounce.out' => 'Bounce Out',
            'bounce.inOut' => 'Bounce InOut',
            'circ.in' => 'Circ In',
            'circ.out' => 'Circ Out',
            'circ.inOut' => 'Circ InOut',
            'expo.in' => 'Expo In',
            'expo.out' => 'Expo Out',
            'expo.inOut' => 'Expo InOut',
        ];
    }

    /**
     * Check if reduced motion is preferred
     */
    public static function is_reduced_motion() {
        // This will be handled via CSS and JavaScript
        // Just a placeholder for future enhancements
        return false;
    }

    /**
     * Add reduced motion CSS
     */
    public static function add_reduced_motion_css() {
        ?>
        <style>
            @media (prefers-reduced-motion: reduce) {
                .egw-widget * {
                    animation-duration: 0.01ms !important;
                    animation-iteration-count: 1 !important;
                    transition-duration: 0.01ms !important;
                }
            }
        </style>
        <?php
    }

    /**
     * Generate unique widget ID
     */
    public static function generate_widget_id($prefix = 'egw') {
        return $prefix . '-' . uniqid();
    }

    /**
     * Parse ScrollTrigger toggle actions
     */
    public static function parse_toggle_actions($actions_string) {
        $valid_actions = ['play', 'pause', 'resume', 'reset', 'restart', 'complete', 'reverse', 'none'];
        $actions = explode(' ', $actions_string);

        $parsed = [
            'onEnter' => 'play',
            'onLeave' => 'none',
            'onEnterBack' => 'none',
            'onLeaveBack' => 'none',
        ];

        if (isset($actions[0]) && in_array($actions[0], $valid_actions)) {
            $parsed['onEnter'] = $actions[0];
        }
        if (isset($actions[1]) && in_array($actions[1], $valid_actions)) {
            $parsed['onLeave'] = $actions[1];
        }
        if (isset($actions[2]) && in_array($actions[2], $valid_actions)) {
            $parsed['onEnterBack'] = $actions[2];
        }
        if (isset($actions[3]) && in_array($actions[3], $valid_actions)) {
            $parsed['onLeaveBack'] = $actions[3];
        }

        return $parsed;
    }

    /**
     * Build GSAP from/to values based on animation type
     */
    public static function get_animation_values($animation_type) {
        $animations = [
            'fade-in' => [
                'from' => ['opacity' => 0],
                'to' => ['opacity' => 1],
            ],
            'slide-up' => [
                'from' => ['opacity' => 0, 'y' => 100],
                'to' => ['opacity' => 1, 'y' => 0],
            ],
            'slide-down' => [
                'from' => ['opacity' => 0, 'y' => -100],
                'to' => ['opacity' => 1, 'y' => 0],
            ],
            'slide-left' => [
                'from' => ['opacity' => 0, 'x' => 100],
                'to' => ['opacity' => 1, 'x' => 0],
            ],
            'slide-right' => [
                'from' => ['opacity' => 0, 'x' => -100],
                'to' => ['opacity' => 1, 'x' => 0],
            ],
            'scale-up' => [
                'from' => ['opacity' => 0, 'scale' => 0.5],
                'to' => ['opacity' => 1, 'scale' => 1],
            ],
            'scale-down' => [
                'from' => ['opacity' => 0, 'scale' => 1.5],
                'to' => ['opacity' => 1, 'scale' => 1],
            ],
            'rotate-in' => [
                'from' => ['opacity' => 0, 'rotation' => -180],
                'to' => ['opacity' => 1, 'rotation' => 0],
            ],
        ];

        return isset($animations[$animation_type]) ? $animations[$animation_type] : $animations['fade-in'];
    }
}

// Initialize
Animation_Handler::init();
