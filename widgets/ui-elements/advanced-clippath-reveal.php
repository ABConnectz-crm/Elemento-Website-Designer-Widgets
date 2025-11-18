<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Advanced Clip-Path Reveal Widget
 * Awwwards-style reveal animations using CSS clip-path
 * Uses only FREE GSAP plugins (Core + ScrollTrigger)
 */
class Advanced_ClipPath_Reveal_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-clippath-reveal';
    }

    public function get_title() {
        return __('Clip-Path Reveal', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-image-rollover';
    }

    public function get_categories() {
        return ['egw-ui-elements'];
    }

    public function get_script_depends() {
        return ['gsap', 'gsap-scrolltrigger', 'egw-clippath-reveal-handler'];
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
            'content_type',
            [
                'label' => __('Content Type', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'image',
                'options' => [
                    'image' => __('Image', 'elementor-gsap-widgets'),
                    'video' => __('Video', 'elementor-gsap-widgets'),
                    'text' => __('Text', 'elementor-gsap-widgets'),
                    'element' => __('Custom Element', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => __('Choose Image', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'content_type' => 'image',
                ],
            ]
        );

        $this->add_control(
            'text_content',
            [
                'label' => __('Text', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Revealed Content', 'elementor-gsap-widgets'),
                'condition' => [
                    'content_type' => 'text',
                ],
            ]
        );

        $this->end_controls_section();

        // Clip Path Settings
        $this->start_controls_section(
            'section_clippath_settings',
            [
                'label' => __('Clip-Path Settings', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'reveal_shape',
            [
                'label' => __('Reveal Shape', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'circle',
                'options' => [
                    'circle' => __('Circle Expand', 'elementor-gsap-widgets'),
                    'polygon-diagonal' => __('Diagonal Wipe', 'elementor-gsap-widgets'),
                    'polygon-center' => __('Center Box Expand', 'elementor-gsap-widgets'),
                    'inset-horizontal' => __('Horizontal Curtain', 'elementor-gsap-widgets'),
                    'inset-vertical' => __('Vertical Curtain', 'elementor-gsap-widgets'),
                    'polygon-diamond' => __('Diamond Expand', 'elementor-gsap-widgets'),
                    'polygon-hexagon' => __('Hexagon Expand', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->add_control(
            'reveal_origin',
            [
                'label' => __('Reveal Origin', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'center',
                'options' => [
                    'center' => __('Center', 'elementor-gsap-widgets'),
                    'top-left' => __('Top Left', 'elementor-gsap-widgets'),
                    'top-right' => __('Top Right', 'elementor-gsap-widgets'),
                    'bottom-left' => __('Bottom Left', 'elementor-gsap-widgets'),
                    'bottom-right' => __('Bottom Right', 'elementor-gsap-widgets'),
                    'mouse' => __('Follow Mouse (Interactive)', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->add_control(
            'reveal_duration',
            [
                'label' => __('Duration (seconds)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1.5,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.3,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
            ]
        );

        $this->add_control(
            'reveal_easing',
            [
                'label' => __('Easing', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'power4.inOut',
                'options' => [
                    'power1.inOut' => __('Power1 InOut', 'elementor-gsap-widgets'),
                    'power2.inOut' => __('Power2 InOut', 'elementor-gsap-widgets'),
                    'power3.inOut' => __('Power3 InOut', 'elementor-gsap-widgets'),
                    'power4.inOut' => __('Power4 InOut (Recommended)', 'elementor-gsap-widgets'),
                    'expo.inOut' => __('Expo InOut', 'elementor-gsap-widgets'),
                    'circ.inOut' => __('Circ InOut', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->add_control(
            'scale_content',
            [
                'label' => __('Scale Content During Reveal', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'elementor-gsap-widgets'),
                'label_off' => __('No', 'elementor-gsap-widgets'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __('Scale content from 0.8 to 1 during reveal', 'elementor-gsap-widgets'),
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_height',
            [
                'label' => __('Height', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', '%'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 500,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .egw-clippath-content' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => __('Background Color', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f0f0f0',
                'selectors' => [
                    '{{WRAPPER}} .egw-clippath-wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ScrollTrigger Controls
        $this->add_scrolltrigger_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $data_attrs = [
            'data-egw-reveal-shape' => $settings['reveal_shape'] ?? 'circle',
            'data-egw-reveal-origin' => $settings['reveal_origin'] ?? 'center',
            'data-egw-reveal-duration' => $settings['reveal_duration']['size'] ?? 1.5,
            'data-egw-reveal-easing' => $settings['reveal_easing'] ?? 'power4.inOut',
            'data-egw-scale-content' => ($settings['scale_content'] === 'yes') ? 'true' : 'false',
        ];

        $anim_attrs = $this->get_animation_attributes($settings);
        $all_attrs = array_merge($data_attrs, $anim_attrs);

        ?>
        <div class="egw-widget egw-clippath-reveal-widget">
            <div class="egw-clippath-wrapper" <?php echo $this->render_attributes_string($all_attrs); ?>>
                <div class="egw-clippath-content">
                    <?php
                    switch ($settings['content_type']) {
                        case 'image':
                            echo '<img src="' . esc_url($settings['image']['url']) . '" alt="" />';
                            break;
                        case 'text':
                            echo '<div class="egw-text-content">' . esc_html($settings['text_content']) . '</div>';
                            break;
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
}
