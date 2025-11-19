<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Underline_Draw_Heading extends Heading_Widget_Base {
    public function get_name() { return 'egw-heading-underline-draw'; }
    public function get_title() { return __('Underline Draw Heading', 'elementor-gsap-widgets'); }
    public function get_icon() { return 'eicon-animation-text'; }

    protected function register_controls() {
        $this->register_heading_controls();
        $this->start_controls_section('section_underline_settings', [
            'label' => __('Underline Settings', 'elementor-gsap-widgets'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        $this->add_control('underline_height', [
            'label' => __('Underline Height (px)', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 3],
            'range' => ['px' => ['min' => 1, 'max' => 20]],
        ]);
        $this->add_control('underline_color', [
            'label' => __('Underline Color', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#000000',
        ]);
        $this->add_control('draw_direction', [
            'label' => __('Draw Direction', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'left-to-right',
            'options' => [
                'left-to-right' => __('Left to Right', 'elementor-gsap-widgets'),
                'right-to-left' => __('Right to Left', 'elementor-gsap-widgets'),
                'center-out' => __('Center Out', 'elementor-gsap-widgets'),
            ],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $attrs = $this->get_heading_attributes($settings, 'underline-draw');
        $attrs['data-egw-underline-height'] = $settings['underline_height']['size'] ?? 3;
        $attrs['data-egw-underline-color'] = $settings['underline_color'] ?? '#000000';
        $attrs['data-egw-draw-direction'] = $settings['draw_direction'] ?? 'left-to-right';
        ?>
        <div class="egw-widget egw-heading-widget">
            <div class="egw-heading-container">
                <<?php echo esc_attr($settings['heading_tag']); ?> class="egw-animated-heading" <?php echo $this->render_attributes_string($attrs); ?>>
                    <?php echo esc_html($settings['heading_text']); ?>
                </<?php echo esc_attr($settings['heading_tag']); ?>>
            </div>
        </div>
        <?php
    }
}
