<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Base Heading Widget Class
 * Common functionality for all heading animation widgets
 */
abstract class Heading_Widget_Base extends Widget_Base {

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
        return ['gsap', 'gsap-scrolltrigger', 'egw-text-splitter', 'egw-heading-animations'];
    }

    /**
     * Add common heading controls
     */
    protected function register_heading_controls() {
        // Content Section
        $this->start_controls_section(
            'section_heading_content',
            [
                'label' => __('Heading Content', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'heading_text',
            [
                'label' => __('Heading Text', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Animated Heading', 'elementor-gsap-widgets'),
                'placeholder' => __('Enter your heading text', 'elementor-gsap-widgets'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'heading_tag',
            [
                'label' => __('HTML Tag', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_align',
            [
                'label' => __('Alignment', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'elementor-gsap-widgets'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'elementor-gsap-widgets'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'elementor-gsap-widgets'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .egw-heading-container' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Typography Section
        $this->start_controls_section(
            'section_heading_typography',
            [
                'label' => __('Typography', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .egw-animated-heading',
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => __('Text Color', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .egw-animated-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_text_stroke',
            [
                'label' => __('Text Stroke Width', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .egw-animated-heading' => '-webkit-text-stroke-width: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'heading_stroke_color',
            [
                'label' => __('Stroke Color', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .egw-animated-heading' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
                'condition' => [
                    'heading_text_stroke[size]!' => 0,
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'heading_text_shadow',
                'selector' => '{{WRAPPER}} .egw-animated-heading',
            ]
        );

        $this->end_controls_section();

        // Animation Settings
        $this->add_animation_controls();
        $this->add_scrolltrigger_controls();
    }

    /**
     * Get heading data attributes
     */
    protected function get_heading_attributes($settings, $animation_type) {
        $attrs = [
            'data-egw-heading-animation' => $animation_type,
            'data-egw-duration' => $settings['animation_duration']['size'] ?? 1,
            'data-egw-delay' => $settings['animation_delay']['size'] ?? 0,
            'data-egw-easing' => $settings['animation_easing'] ?? 'power2.out',
        ];

        // Add ScrollTrigger attributes
        if (isset($settings['enable_scrolltrigger']) && $settings['enable_scrolltrigger'] === 'yes') {
            $attrs['data-egw-scrolltrigger'] = 'true';
            $attrs['data-egw-trigger-start'] = $settings['trigger_start'] ?? 'top 80%';
            $attrs['data-egw-trigger-end'] = $settings['trigger_end'] ?? 'bottom 20%';

            if (isset($settings['trigger_scrub']) && $settings['trigger_scrub'] === 'yes') {
                $attrs['data-egw-scrub'] = $settings['trigger_scrub_smooth']['size'] ?? 1;
            }

            if (isset($settings['trigger_markers']) && $settings['trigger_markers'] === 'yes') {
                $attrs['data-egw-markers'] = 'true';
            }
        }

        return $attrs;
    }
}
