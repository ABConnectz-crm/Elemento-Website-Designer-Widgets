<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Parallax_Image_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-parallax-image';
    }

    public function get_title() {
        return __('Parallax Image', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-parallax';
    }

    public function get_categories() {
        return ['egw-image-animations'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_image',
            [
                'label' => __('Image', 'elementor-gsap-widgets'),
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
            ]
        );

        $this->add_control(
            'parallax_speed',
            [
                'label' => __('Parallax Speed', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 2,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.5,
                ],
            ]
        );

        $this->add_control(
            'parallax_direction',
            [
                'label' => __('Direction', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'vertical',
                'options' => [
                    'vertical' => __('Vertical', 'elementor-gsap-widgets'),
                    'horizontal' => __('Horizontal', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_dimensions',
            [
                'label' => __('Dimensions', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label' => __('Height', 'elementor-gsap-widgets'),
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
                    'size' => 500,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .egw-parallax-container' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .egw-parallax-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $image_url = $settings['image']['url'];

        ?>
        <div class="egw-widget egw-parallax-image-widget">
            <div class="egw-parallax-container">
                <img src="<?php echo esc_url($image_url); ?>"
                     class="egw-parallax-image"
                     data-egw-parallax="true"
                     data-egw-parallax-speed="<?php echo esc_attr($settings['parallax_speed']['size']); ?>"
                     data-egw-parallax-direction="<?php echo esc_attr($settings['parallax_direction']); ?>"
                     alt="">
            </div>
        </div>
        <?php
    }
}
