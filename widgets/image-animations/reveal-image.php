<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Reveal_Image_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-reveal-image';
    }

    public function get_title() {
        return __('Reveal Image', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-animation';
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
            'reveal_direction',
            [
                'label' => __('Reveal Direction', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __('Left to Right', 'elementor-gsap-widgets'),
                    'right' => __('Right to Left', 'elementor-gsap-widgets'),
                    'top' => __('Top to Bottom', 'elementor-gsap-widgets'),
                    'bottom' => __('Bottom to Top', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'width',
            [
                'label' => __('Width', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 1200,
                    ],
                ],
                'default' => [
                    'size' => 800,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .egw-reveal-wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .egw-reveal-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->add_animation_controls();
        $this->add_scrolltrigger_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $image_url = $settings['image']['url'];
        $anim_attrs = $this->get_animation_attributes($settings);
        $anim_attrs['data-egw-reveal'] = 'true';
        $anim_attrs['data-egw-reveal-direction'] = $settings['reveal_direction'];

        ?>
        <div class="egw-widget egw-reveal-image-widget">
            <div class="egw-reveal-wrapper" <?php echo $this->render_attributes_string($anim_attrs); ?>>
                <img src="<?php echo esc_url($image_url); ?>" alt="">
            </div>
        </div>
        <?php
    }
}
