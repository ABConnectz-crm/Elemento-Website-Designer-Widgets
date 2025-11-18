<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Skew & Stagger Text Reveal Widget
 * Premium text reveal with physics-based skew animation
 * Uses only FREE GSAP plugins (Core + ScrollTrigger)
 */
class Skew_Reveal_Text_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-skew-reveal-text';
    }

    public function get_title() {
        return __('Skew Reveal Text', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-animation-text';
    }

    public function get_categories() {
        return ['egw-text-animations'];
    }

    public function get_script_depends() {
        return ['gsap', 'gsap-scrolltrigger', 'egw-text-splitter', 'egw-skew-reveal-handler'];
    }

    public function get_style_depends() {
        return ['egw-base-animations', 'egw-widget-styles'];
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
                'default' => __('Premium Text Animation with Physics-Based Motion', 'elementor-gsap-widgets'),
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

        $this->add_control(
            'split_type',
            [
                'label' => __('Split By', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'lines',
                'options' => [
                    'lines' => __('Lines', 'elementor-gsap-widgets'),
                    'words' => __('Words', 'elementor-gsap-widgets'),
                    'chars' => __('Characters', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->end_controls_section();

        // Animation Settings
        $this->start_controls_section(
            'section_reveal_settings',
            [
                'label' => __('Reveal Animation', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'reveal_direction',
            [
                'label' => __('Reveal Direction', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'up',
                'options' => [
                    'up' => __('From Bottom', 'elementor-gsap-widgets'),
                    'down' => __('From Top', 'elementor-gsap-widgets'),
                    'left' => __('From Right', 'elementor-gsap-widgets'),
                    'right' => __('From Left', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->add_control(
            'skew_amount',
            [
                'label' => __('Skew Amount (deg)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 7,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                        'step' => 1,
                    ],
                ],
                'description' => __('Skew distortion during animation', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'rotation_3d',
            [
                'label' => __('3D Rotation (deg)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => -45,
                ],
                'range' => [
                    'px' => [
                        'min' => -90,
                        'max' => 90,
                        'step' => 1,
                    ],
                ],
                'description' => __('Initial 3D rotation for depth', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'distance',
            [
                'label' => __('Distance (px)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 100,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 10,
                    ],
                ],
                'description' => __('Travel distance during animation', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'use_clip_mask',
            [
                'label' => __('Use Clip Mask', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'elementor-gsap-widgets'),
                'label_off' => __('No', 'elementor-gsap-widgets'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __('Hide overflow with clip-path', 'elementor-gsap-widgets'),
            ]
        );

        $this->end_controls_section();

        // Stagger Settings
        $this->start_controls_section(
            'section_stagger_settings',
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
            'stagger_ease',
            [
                'label' => __('Stagger Ease', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __('None (linear)', 'elementor-gsap-widgets'),
                    'power1.in' => __('Power1 In', 'elementor-gsap-widgets'),
                    'power1.out' => __('Power1 Out', 'elementor-gsap-widgets'),
                    'power2.in' => __('Power2 In', 'elementor-gsap-widgets'),
                    'power2.out' => __('Power2 Out', 'elementor-gsap-widgets'),
                ],
                'description' => __('Easing curve for stagger progression', 'elementor-gsap-widgets'),
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
                'selector' => '{{WRAPPER}} .egw-skew-reveal-text',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .egw-skew-reveal-text' => 'color: {{VALUE}};',
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
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .egw-skew-reveal-container' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'perspective',
            [
                'label' => __('Perspective (px)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1000,
                ],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 3000,
                        'step' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .egw-skew-reveal-container' => 'perspective: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_section();

        // Add common animation controls
        $this->add_animation_controls();
        $this->add_scrolltrigger_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $tag = $settings['html_tag'];

        $data_attrs = [
            'data-egw-reveal-type' => 'skew-stagger',
            'data-egw-split-type' => $settings['split_type'] ?? 'lines',
            'data-egw-reveal-direction' => $settings['reveal_direction'] ?? 'up',
            'data-egw-skew-amount' => $settings['skew_amount']['size'] ?? 7,
            'data-egw-rotation-3d' => $settings['rotation_3d']['size'] ?? -45,
            'data-egw-distance' => $settings['distance']['size'] ?? 100,
            'data-egw-use-clip' => ($settings['use_clip_mask'] === 'yes') ? 'true' : 'false',
            'data-egw-stagger-amount' => $settings['stagger_amount']['size'] ?? 0.1,
            'data-egw-stagger-from' => $settings['stagger_from'] ?? 'start',
            'data-egw-stagger-ease' => $settings['stagger_ease'] ?? 'none',
        ];

        $anim_attrs = $this->get_animation_attributes($settings);
        $all_attrs = array_merge($data_attrs, $anim_attrs);

        $text = $settings['text'];

        ?>
        <div class="egw-widget egw-skew-reveal-widget">
            <div class="egw-skew-reveal-container" style="transform-style: preserve-3d;">
                <<?php echo esc_attr($tag); ?> class="egw-skew-reveal-text" <?php echo $this->render_attributes_string($all_attrs); ?>>
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
        <div class="egw-widget egw-skew-reveal-widget">
            <div class="egw-skew-reveal-container" style="transform-style: preserve-3d;">
                <{{{ tag }}} class="egw-skew-reveal-text"
                    data-egw-reveal-type="skew-stagger"
                    data-egw-split-type="{{ settings.split_type }}"
                    data-egw-reveal-direction="{{ settings.reveal_direction }}"
                    data-egw-skew-amount="{{ settings.skew_amount.size }}"
                    data-egw-rotation-3d="{{ settings.rotation_3d.size }}"
                    data-egw-distance="{{ settings.distance.size }}"
                    data-egw-use-clip="{{ settings.use_clip_mask === 'yes' ? 'true' : 'false' }}"
                    data-egw-stagger-amount="{{ settings.stagger_amount.size }}"
                    data-egw-stagger-from="{{ settings.stagger_from }}"
                    data-egw-stagger-ease="{{ settings.stagger_ease }}">
                    {{{ text }}}
                </{{{ tag }}}>
            </div>
        </div>
        <?php
    }
}
