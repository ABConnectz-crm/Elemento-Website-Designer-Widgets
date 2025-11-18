<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Gradient_Background_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-gradient-background';
    }

    public function get_title() {
        return __('Gradient Background', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-background';
    }

    public function get_categories() {
        return ['egw-backgrounds'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'content',
            [
                'label' => __('Content', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => __('<h2>Gradient Background</h2><p>Animated gradient background</p>', 'elementor-gsap-widgets'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_gradient',
            [
                'label' => __('Gradient Settings', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'color_1',
            [
                'label' => __('Color 1', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ee7752',
            ]
        );

        $this->add_control(
            'color_2',
            [
                'label' => __('Color 2', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#e73c7e',
            ]
        );

        $this->add_control(
            'color_3',
            [
                'label' => __('Color 3', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#23a6d5',
            ]
        );

        $this->add_control(
            'color_4',
            [
                'label' => __('Color 4', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#23d5ab',
            ]
        );

        $this->add_control(
            'animation_duration',
            [
                'label' => __('Animation Duration (seconds)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'size' => 15,
                ],
            ]
        );

        $this->add_responsive_control(
            'min_height',
            [
                'label' => __('Min Height', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
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
                    'size' => 400,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .egw-gradient-background-widget' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $gradient = sprintf(
            'linear-gradient(-45deg, %s, %s, %s, %s)',
            $settings['color_1'],
            $settings['color_2'],
            $settings['color_3'],
            $settings['color_4']
        );
        $duration = $settings['animation_duration']['size'];

        ?>
        <div class="egw-widget egw-gradient-background-widget">
            <div class="egw-gradient-layer egw-gradient-bg"
                 style="background: <?php echo esc_attr($gradient); ?>; animation-duration: <?php echo esc_attr($duration); ?>s;"
                 data-egw-gradient-anim="true"
                 data-egw-gradient-duration="<?php echo esc_attr($duration); ?>"></div>
            <div class="egw-content">
                <?php echo $settings['content']; ?>
            </div>
        </div>
        <?php
    }
}
