<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Glassmorphism Text Widget
 */
class Glassmorphism_Text_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-glassmorphism-text';
    }

    public function get_title() {
        return __('Glassmorphism Text', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-text';
    }

    public function get_categories() {
        return ['egw-text-animations'];
    }

    protected function get_animation_types() {
        return [
            'fade-in' => __('Fade In', 'elementor-gsap-widgets'),
            'slide-up' => __('Slide Up', 'elementor-gsap-widgets'),
            'slide-down' => __('Slide Down', 'elementor-gsap-widgets'),
            'scale-up' => __('Scale Up', 'elementor-gsap-widgets'),
            'blur-in' => __('Blur In', 'elementor-gsap-widgets'),
        ];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'text',
            [
                'label' => __('Text', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Glassmorphism Effect', 'elementor-gsap-widgets'),
                'placeholder' => __('Enter your text', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'html_tag',
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

        $this->end_controls_section();

        // Glassmorphism Style
        $this->start_controls_section(
            'section_glassmorphism_style',
            [
                'label' => __('Glassmorphism Style', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'glass_theme',
            [
                'label' => __('Theme', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'light',
                'options' => [
                    'light' => __('Light', 'elementor-gsap-widgets'),
                    'dark' => __('Dark', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->add_control(
            'blur_amount',
            [
                'label' => __('Blur Amount', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .egw-glassmorphism' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );

        $this->add_control(
            'background_opacity',
            [
                'label' => __('Background Opacity', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.1,
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
            'border_radius',
            [
                'label' => __('Border Radius', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .egw-glassmorphism' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Typography
        $this->start_controls_section(
            'section_typography',
            [
                'label' => __('Typography', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .egw-text-content',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .egw-text-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_shadow',
            [
                'label' => __('Text Shadow', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT_SHADOW,
                'selectors' => [
                    '{{WRAPPER}} .egw-text-content' => 'text-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{COLOR}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Add common animation controls
        $this->add_animation_controls();

        // Add ScrollTrigger controls
        $this->add_scrolltrigger_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $tag = $settings['html_tag'];
        $theme_class = $settings['glass_theme'] === 'dark' ? 'egw-glassmorphism-dark' : 'egw-glassmorphism';

        // Get animation attributes
        $anim_attrs = $this->get_animation_attributes($settings);

        // Background opacity
        $bg_opacity = $settings['background_opacity']['size'];
        $bg_color = $settings['glass_theme'] === 'dark'
            ? "rgba(0, 0, 0, {$bg_opacity})"
            : "rgba(255, 255, 255, {$bg_opacity})";

        $inline_style = "background: {$bg_color};";

        ?>
        <div class="egw-widget egw-glassmorphism-text-widget">
            <div class="<?php echo esc_attr($theme_class); ?>" style="<?php echo esc_attr($inline_style); ?>" <?php echo $this->render_attributes_string($anim_attrs); ?>>
                <<?php echo esc_attr($tag); ?> class="egw-text-content">
                    <?php echo esc_html($settings['text']); ?>
                </<?php echo esc_attr($tag); ?>>
            </div>
        </div>
        <?php
    }
}
