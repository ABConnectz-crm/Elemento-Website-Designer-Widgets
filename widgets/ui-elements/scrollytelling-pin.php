<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Scrollytelling Pin Container Widget
 * Creates Awwwards-style pinned scroll sections with stacking cards
 * Uses only FREE GSAP plugins (Core + ScrollTrigger)
 */
class Scrollytelling_Pin_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-scrollytelling-pin';
    }

    public function get_title() {
        return __('Scrollytelling Pin Container', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-inner-section';
    }

    public function get_categories() {
        return ['egw-ui-elements'];
    }

    public function get_script_depends() {
        return ['gsap', 'gsap-scrolltrigger', 'egw-scrollytelling-pin-handler'];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Panels', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'panel_title',
            [
                'label' => __('Panel Title', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Panel Title', 'elementor-gsap-widgets'),
            ]
        );

        $repeater->add_control(
            'panel_content',
            [
                'label' => __('Content', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Panel content goes here', 'elementor-gsap-widgets'),
            ]
        );

        $repeater->add_control(
            'panel_background',
            [
                'label' => __('Background Color', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f0f0f0',
            ]
        );

        $repeater->add_control(
            'panel_image',
            [
                'label' => __('Background Image', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'panels',
            [
                'label' => __('Panels', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'panel_title' => __('Panel 1', 'elementor-gsap-widgets'),
                        'panel_content' => __('First panel content', 'elementor-gsap-widgets'),
                        'panel_background' => '#e74c3c',
                    ],
                    [
                        'panel_title' => __('Panel 2', 'elementor-gsap-widgets'),
                        'panel_content' => __('Second panel content', 'elementor-gsap-widgets'),
                        'panel_background' => '#3498db',
                    ],
                    [
                        'panel_title' => __('Panel 3', 'elementor-gsap-widgets'),
                        'panel_content' => __('Third panel content', 'elementor-gsap-widgets'),
                        'panel_background' => '#2ecc71',
                    ],
                ],
                'title_field' => '{{{ panel_title }}}',
            ]
        );

        $this->end_controls_section();

        // Pin Settings
        $this->start_controls_section(
            'section_pin_settings',
            [
                'label' => __('Pin Settings', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'effect_type',
            [
                'label' => __('Effect Type', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'stack',
                'options' => [
                    'stack' => __('Stacking Cards', 'elementor-gsap-widgets'),
                    'slide' => __('Slide Over', 'elementor-gsap-widgets'),
                    'fade-scale' => __('Fade & Scale', 'elementor-gsap-widgets'),
                    'rotate' => __('Rotate Out', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->add_control(
            'pin_spacer',
            [
                'label' => __('Pin Spacer (vh)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 100,
                ],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 300,
                        'step' => 10,
                    ],
                ],
                'description' => __('Extra scroll distance per panel', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'scale_amount',
            [
                'label' => __('Scale Amount', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.9,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.5,
                        'max' => 1,
                        'step' => 0.05,
                    ],
                ],
                'description' => __('Scale factor for background panels', 'elementor-gsap-widgets'),
                'condition' => [
                    'effect_type' => ['stack', 'fade-scale'],
                ],
            ]
        );

        $this->add_control(
            'stack_offset',
            [
                'label' => __('Stack Offset (px)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 40,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 5,
                    ],
                ],
                'description' => __('Vertical offset for stacked panels', 'elementor-gsap-widgets'),
                'condition' => [
                    'effect_type' => 'stack',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Panel Style', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'panel_height',
            [
                'label' => __('Panel Height', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'default' => [
                    'size' => 100,
                    'unit' => 'vh',
                ],
                'range' => [
                    'px' => [
                        'min' => 300,
                        'max' => 1200,
                    ],
                    'vh' => [
                        'min' => 50,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .egw-pin-panel' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'panel_border_radius',
            [
                'label' => __('Border Radius', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .egw-pin-panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'panel_box_shadow',
                'selector' => '{{WRAPPER}} .egw-pin-panel',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $data_attrs = [
            'data-egw-effect-type' => $settings['effect_type'] ?? 'stack',
            'data-egw-pin-spacer' => $settings['pin_spacer']['size'] ?? 100,
            'data-egw-scale-amount' => $settings['scale_amount']['size'] ?? 0.9,
            'data-egw-stack-offset' => $settings['stack_offset']['size'] ?? 40,
        ];

        ?>
        <div class="egw-widget egw-scrollytelling-pin-widget">
            <div class="egw-pin-container" <?php echo $this->render_attributes_string($data_attrs); ?>>
                <?php foreach ($settings['panels'] as $index => $panel) : ?>
                    <div class="egw-pin-panel" data-panel-index="<?php echo $index; ?>"
                         style="background-color: <?php echo esc_attr($panel['panel_background']); ?>;
                                <?php if (!empty($panel['panel_image']['url'])) : ?>
                                background-image: url('<?php echo esc_url($panel['panel_image']['url']); ?>');
                                background-size: cover;
                                background-position: center;
                                <?php endif; ?>">
                        <div class="egw-panel-content">
                            <h2><?php echo esc_html($panel['panel_title']); ?></h2>
                            <p><?php echo esc_html($panel['panel_content']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
