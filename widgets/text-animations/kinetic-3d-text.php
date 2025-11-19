<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Kinetic 3D Cylinder Text Widget
 * Creates text that wraps around a 3D cylinder with customizable rotation
 * Uses only FREE GSAP plugins (Core + ScrollTrigger)
 */
class Kinetic_3D_Text_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-kinetic-3d-text';
    }

    public function get_title() {
        return __('Kinetic 3D Cylinder Text', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-3d-object';
    }

    public function get_categories() {
        return ['egw-text-animations'];
    }

    public function get_script_depends() {
        return ['gsap', 'gsap-scrolltrigger', 'egw-text-splitter', 'egw-kinetic-3d-handler'];
    }

    public function get_style_depends() {
        return ['egw-base-animations', 'egw-widget-styles'];
    }

    protected function get_animation_types() {
        return [
            'scroll-rotate' => __('Scroll Rotate', 'elementor-gsap-widgets'),
            'auto-rotate' => __('Auto Rotate', 'elementor-gsap-widgets'),
            'hover-rotate' => __('Hover Rotate', 'elementor-gsap-widgets'),
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
                'default' => __('KINETIC MOTION', 'elementor-gsap-widgets'),
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
                    'p' => 'p',
                ],
            ]
        );

        $this->end_controls_section();

        // 3D Settings
        $this->start_controls_section(
            'section_3d_settings',
            [
                'label' => __('3D Cylinder Settings', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'cylinder_radius',
            [
                'label' => __('Cylinder Radius (px)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 200,
                ],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 1000,
                        'step' => 10,
                    ],
                ],
                'description' => __('Radius of the virtual cylinder', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'perspective',
            [
                'label' => __('Perspective (px)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1000,
                ],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 3000,
                        'step' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .egw-kinetic-3d-container' => 'perspective: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'rotation_axis',
            [
                'label' => __('Rotation Axis', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'y',
                'options' => [
                    'x' => __('X Axis (Horizontal)', 'elementor-gsap-widgets'),
                    'y' => __('Y Axis (Vertical)', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->add_control(
            'char_spacing',
            [
                'label' => __('Character Spacing (deg)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 8,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 30,
                        'step' => 0.5,
                    ],
                ],
                'description' => __('Degrees between each character', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'depth_fade',
            [
                'label' => __('Depth Fade', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'elementor-gsap-widgets'),
                'label_off' => __('No', 'elementor-gsap-widgets'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __('Fade characters based on Z-depth', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'depth_blur',
            [
                'label' => __('Depth Blur', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'elementor-gsap-widgets'),
                'label_off' => __('No', 'elementor-gsap-widgets'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => __('Blur characters based on Z-depth', 'elementor-gsap-widgets'),
            ]
        );

        $this->end_controls_section();

        // Rotation Settings
        $this->start_controls_section(
            'section_rotation',
            [
                'label' => __('Rotation Settings', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'rotation_speed',
            [
                'label' => __('Rotation Speed', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
            ]
        );

        $this->add_control(
            'rotation_direction',
            [
                'label' => __('Rotation Direction', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'clockwise',
                'options' => [
                    'clockwise' => __('Clockwise', 'elementor-gsap-widgets'),
                    'counter-clockwise' => __('Counter-Clockwise', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->add_control(
            'initial_rotation',
            [
                'label' => __('Initial Rotation (deg)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 360,
                        'step' => 1,
                    ],
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
                'selector' => '{{WRAPPER}} .egw-kinetic-3d-text',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .egw-kinetic-3d-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_align',
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .egw-kinetic-3d-container' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_stroke_width',
            [
                'label' => __('Text Stroke Width', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .egw-kinetic-3d-text' => '-webkit-text-stroke-width: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'text_stroke_color',
            [
                'label' => __('Text Stroke Color', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .egw-kinetic-3d-text' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
                'condition' => [
                    'text_stroke_width[size]!' => 0,
                ],
            ]
        );

        $this->end_controls_section();

        // Add specific animation controls for Kinetic 3D
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
                'default' => 'auto-rotate',
                'options' => $this->get_animation_types(),
            ]
        );

        $this->end_controls_section();

        $this->add_scrolltrigger_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $tag = $settings['html_tag'];

        // Build data attributes
        $data_attrs = [
            'data-egw-3d-type' => 'kinetic-cylinder',
            'data-egw-animation' => $settings['animation_type'] ?? 'scroll-rotate',
            'data-egw-cylinder-radius' => $settings['cylinder_radius']['size'] ?? 200,
            'data-egw-rotation-axis' => $settings['rotation_axis'] ?? 'y',
            'data-egw-char-spacing' => $settings['char_spacing']['size'] ?? 8,
            'data-egw-depth-fade' => ($settings['depth_fade'] === 'yes') ? 'true' : 'false',
            'data-egw-depth-blur' => ($settings['depth_blur'] === 'yes') ? 'true' : 'false',
            'data-egw-rotation-speed' => $settings['rotation_speed']['size'] ?? 1,
            'data-egw-rotation-direction' => $settings['rotation_direction'] ?? 'clockwise',
            'data-egw-initial-rotation' => $settings['initial_rotation']['size'] ?? 0,
        ];

        // Add animation attributes
        $anim_attrs = $this->get_animation_attributes($settings);
        $all_attrs = array_merge($data_attrs, $anim_attrs);

        $text = $settings['text'];

        ?>
        <div class="egw-widget egw-kinetic-3d-widget">
            <div class="egw-kinetic-3d-container" style="transform-style: preserve-3d;">
                <<?php echo esc_attr($tag); ?> class="egw-kinetic-3d-text" <?php echo $this->render_attributes_string($all_attrs); ?>>
                    <?php echo esc_html($text); ?>
                </<?php echo esc_attr($tag); ?>>
            </div>
        </div>
        <?php
    }

    protected function content_template() {
        ?>
        <#
        var tag = settings.html_tag;
        var text = settings.text;
        #>
        <div class="egw-widget egw-kinetic-3d-widget">
            <div class="egw-kinetic-3d-container" style="transform-style: preserve-3d;">
                <{{{ tag }}} class="egw-kinetic-3d-text"
                    data-egw-3d-type="kinetic-cylinder"
                    data-egw-animation="{{ settings.animation_type }}"
                    data-egw-cylinder-radius="{{ settings.cylinder_radius.size }}"
                    data-egw-rotation-axis="{{ settings.rotation_axis }}"
                    data-egw-char-spacing="{{ settings.char_spacing.size }}"
                    data-egw-depth-fade="{{ settings.depth_fade === 'yes' ? 'true' : 'false' }}"
                    data-egw-depth-blur="{{ settings.depth_blur === 'yes' ? 'true' : 'false' }}"
                    data-egw-rotation-speed="{{ settings.rotation_speed.size }}"
                    data-egw-rotation-direction="{{ settings.rotation_direction }}"
                    data-egw-initial-rotation="{{ settings.initial_rotation.size }}">
                    {{{ text }}}
                </{{{ tag }}}>
            </div>
        </div>
        <?php
    }
}
