<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Wave_Background_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-wave-background';
    }

    public function get_title() {
        return __('Wave Background', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-anchor';
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
                'default' => __('<h2>Wave Background</h2><p>Animated wave background</p>', 'elementor-gsap-widgets'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_wave_settings',
            [
                'label' => __('Wave Settings', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'wave_color',
            [
                'label' => __('Wave Color', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4facfe',
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => __('Background Color', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#00f2fe',
                'selectors' => [
                    '{{WRAPPER}} .egw-wave-background-widget' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'wave_opacity',
            [
                'label' => __('Wave Opacity', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 1,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.4,
                ],
            ]
        );

        $this->add_control(
            'wave_speed',
            [
                'label' => __('Wave Speed (seconds)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'size' => 10,
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
                    'size' => 300,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .egw-wave-background-widget' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $wave_color = $settings['wave_color'];
        $opacity = $settings['wave_opacity']['size'];
        $speed = $settings['wave_speed']['size'];

        ?>
        <div class="egw-widget egw-wave-background-widget egw-wave-bg">
            <div class="egw-wave-container">
                <svg class="egw-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none" style="animation-duration: <?php echo esc_attr($speed); ?>s;">
                    <path fill="<?php echo esc_attr($wave_color); ?>" fill-opacity="<?php echo esc_attr($opacity); ?>" d="M0,96L48,112C96,128,192,160,288,186.7C384,213,480,235,576,213.3C672,192,768,128,864,128C960,128,1056,192,1152,197.3C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                </svg>
            </div>
            <div class="egw-content">
                <?php echo $settings['content']; ?>
            </div>
        </div>
        <?php
    }
}
