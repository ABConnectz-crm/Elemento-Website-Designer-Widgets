<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Base Widget Class
 *
 * All GSAP widgets extend this base class for common functionality
 */
abstract class Widget_Base extends \Elementor\Widget_Base {

    /**
     * Get widget categories
     */
    public function get_categories() {
        return ['egw-text-animations'];
    }

    /**
     * Get script dependencies
     */
    public function get_script_depends() {
        return ['gsap', 'gsap-scrolltrigger', 'egw-animation-utilities'];
    }

    /**
     * Get style dependencies
     */
    public function get_style_depends() {
        return ['egw-base-animations', 'egw-widget-styles'];
    }

    /**
     * Add common animation controls
     */
    protected function add_animation_controls() {
        $this->start_controls_section(
            'section_gsap_animation',
            [
                'label' => __('GSAP Animation', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'animation_type',
            [
                'label' => __('Animation Type', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'fade-in',
                'options' => $this->get_animation_types(),
            ]
        );

        $this->add_control(
            'animation_duration',
            [
                'label' => __('Duration (seconds)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
            ]
        );

        $this->add_control(
            'animation_delay',
            [
                'label' => __('Delay (seconds)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
            ]
        );

        $this->add_control(
            'animation_easing',
            [
                'label' => __('Easing', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'power2.out',
                'options' => [
                    'none' => __('None (linear)', 'elementor-gsap-widgets'),
                    'power1.in' => __('Power1 In', 'elementor-gsap-widgets'),
                    'power1.out' => __('Power1 Out', 'elementor-gsap-widgets'),
                    'power1.inOut' => __('Power1 InOut', 'elementor-gsap-widgets'),
                    'power2.in' => __('Power2 In', 'elementor-gsap-widgets'),
                    'power2.out' => __('Power2 Out', 'elementor-gsap-widgets'),
                    'power2.inOut' => __('Power2 InOut', 'elementor-gsap-widgets'),
                    'power3.in' => __('Power3 In', 'elementor-gsap-widgets'),
                    'power3.out' => __('Power3 Out', 'elementor-gsap-widgets'),
                    'power3.inOut' => __('Power3 InOut', 'elementor-gsap-widgets'),
                    'power4.in' => __('Power4 In', 'elementor-gsap-widgets'),
                    'power4.out' => __('Power4 Out', 'elementor-gsap-widgets'),
                    'power4.inOut' => __('Power4 InOut', 'elementor-gsap-widgets'),
                    'back.in' => __('Back In', 'elementor-gsap-widgets'),
                    'back.out' => __('Back Out', 'elementor-gsap-widgets'),
                    'back.inOut' => __('Back InOut', 'elementor-gsap-widgets'),
                    'elastic.in' => __('Elastic In', 'elementor-gsap-widgets'),
                    'elastic.out' => __('Elastic Out', 'elementor-gsap-widgets'),
                    'elastic.inOut' => __('Elastic InOut', 'elementor-gsap-widgets'),
                    'bounce.in' => __('Bounce In', 'elementor-gsap-widgets'),
                    'bounce.out' => __('Bounce Out', 'elementor-gsap-widgets'),
                    'bounce.inOut' => __('Bounce InOut', 'elementor-gsap-widgets'),
                    'circ.in' => __('Circ In', 'elementor-gsap-widgets'),
                    'circ.out' => __('Circ Out', 'elementor-gsap-widgets'),
                    'circ.inOut' => __('Circ InOut', 'elementor-gsap-widgets'),
                    'expo.in' => __('Expo In', 'elementor-gsap-widgets'),
                    'expo.out' => __('Expo Out', 'elementor-gsap-widgets'),
                    'expo.inOut' => __('Expo InOut', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Add ScrollTrigger controls
     */
    protected function add_scrolltrigger_controls() {
        $this->start_controls_section(
            'section_scrolltrigger',
            [
                'label' => __('ScrollTrigger Settings', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'enable_scrolltrigger',
            [
                'label' => __('Enable ScrollTrigger', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'elementor-gsap-widgets'),
                'label_off' => __('No', 'elementor-gsap-widgets'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'trigger_start',
            [
                'label' => __('Trigger Start', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'top 80%',
                'description' => __('E.g., "top center", "top 80%", "top bottom"', 'elementor-gsap-widgets'),
                'condition' => [
                    'enable_scrolltrigger' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'trigger_end',
            [
                'label' => __('Trigger End', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'bottom 20%',
                'description' => __('E.g., "bottom center", "bottom 20%"', 'elementor-gsap-widgets'),
                'condition' => [
                    'enable_scrolltrigger' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'trigger_scrub',
            [
                'label' => __('Scrub', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'elementor-gsap-widgets'),
                'label_off' => __('No', 'elementor-gsap-widgets'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => __('Link animation directly to scroll position', 'elementor-gsap-widgets'),
                'condition' => [
                    'enable_scrolltrigger' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'trigger_scrub_smooth',
            [
                'label' => __('Scrub Smoothness', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'condition' => [
                    'enable_scrolltrigger' => 'yes',
                    'trigger_scrub' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'trigger_pin',
            [
                'label' => __('Pin Element', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'elementor-gsap-widgets'),
                'label_off' => __('No', 'elementor-gsap-widgets'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => __('Pin element during scroll', 'elementor-gsap-widgets'),
                'condition' => [
                    'enable_scrolltrigger' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'trigger_toggleactions',
            [
                'label' => __('Toggle Actions', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'play none none none',
                'description' => __('onEnter onLeave onEnterBack onLeaveBack (play/pause/resume/reset/restart/complete/reverse/none)', 'elementor-gsap-widgets'),
                'condition' => [
                    'enable_scrolltrigger' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'trigger_markers',
            [
                'label' => __('Show Markers (Debug)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'elementor-gsap-widgets'),
                'label_off' => __('No', 'elementor-gsap-widgets'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'enable_scrolltrigger' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Add stagger controls (for text animations)
     */
    protected function add_stagger_controls() {
        $this->start_controls_section(
            'section_stagger',
            [
                'label' => __('Stagger Settings', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'stagger_amount',
            [
                'label' => __('Stagger Amount (seconds)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.05,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
            ]
        );

        $this->add_control(
            'stagger_from',
            [
                'label' => __('Stagger From', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'start',
                'options' => [
                    'start' => __('Start', 'elementor-gsap-widgets'),
                    'center' => __('Center', 'elementor-gsap-widgets'),
                    'end' => __('End', 'elementor-gsap-widgets'),
                    'edges' => __('Edges', 'elementor-gsap-widgets'),
                    'random' => __('Random', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->add_control(
            'preserve_word_integrity',
            [
                'label' => __('Preserve Word Integrity', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'elementor-gsap-widgets'),
                'label_off' => __('No', 'elementor-gsap-widgets'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __('Keep words together during animation', 'elementor-gsap-widgets'),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Get animation types - to be overridden by child classes
     */
    protected function get_animation_types() {
        return [
            'fade-in' => __('Fade In', 'elementor-gsap-widgets'),
            'slide-up' => __('Slide Up', 'elementor-gsap-widgets'),
            'slide-down' => __('Slide Down', 'elementor-gsap-widgets'),
            'slide-left' => __('Slide Left', 'elementor-gsap-widgets'),
            'slide-right' => __('Slide Right', 'elementor-gsap-widgets'),
            'scale-up' => __('Scale Up', 'elementor-gsap-widgets'),
            'scale-down' => __('Scale Down', 'elementor-gsap-widgets'),
            'rotate-in' => __('Rotate In', 'elementor-gsap-widgets'),
        ];
    }

    /**
     * Build animation data attributes
     */
    protected function get_animation_attributes($settings) {
        $attributes = [
            'data-egw-animation' => $settings['animation_type'] ?? 'fade-in',
            'data-egw-duration' => $settings['animation_duration']['size'] ?? 1,
            'data-egw-delay' => $settings['animation_delay']['size'] ?? 0,
            'data-egw-easing' => $settings['animation_easing'] ?? 'power2.out',
        ];

        if (isset($settings['enable_scrolltrigger']) && $settings['enable_scrolltrigger'] === 'yes') {
            $attributes['data-egw-scrolltrigger'] = 'true';
            $attributes['data-egw-trigger-start'] = $settings['trigger_start'] ?? 'top 80%';
            $attributes['data-egw-trigger-end'] = $settings['trigger_end'] ?? 'bottom 20%';

            if (isset($settings['trigger_scrub']) && $settings['trigger_scrub'] === 'yes') {
                $scrub_value = $settings['trigger_scrub_smooth']['size'] ?? 1;
                $attributes['data-egw-scrub'] = $scrub_value;
            }

            if (isset($settings['trigger_pin']) && $settings['trigger_pin'] === 'yes') {
                $attributes['data-egw-pin'] = 'true';
            }

            $attributes['data-egw-toggleactions'] = $settings['trigger_toggleactions'] ?? 'play none none none';

            if (isset($settings['trigger_markers']) && $settings['trigger_markers'] === 'yes') {
                $attributes['data-egw-markers'] = 'true';
            }
        }

        return $attributes;
    }

    /**
     * Build stagger data attributes
     */
    protected function get_stagger_attributes($settings) {
        return [
            'data-egw-stagger' => $settings['stagger_amount']['size'] ?? 0.05,
            'data-egw-stagger-from' => $settings['stagger_from'] ?? 'start',
            'data-egw-preserve-words' => isset($settings['preserve_word_integrity']) && $settings['preserve_word_integrity'] === 'yes' ? 'true' : 'false',
        ];
    }

    /**
     * Render attributes as HTML
     */
    protected function render_attributes_string($attributes) {
        $output = '';
        foreach ($attributes as $key => $value) {
            $output .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
        }
        return $output;
    }
}
