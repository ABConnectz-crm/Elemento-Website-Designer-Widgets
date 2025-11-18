<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Minimal_Fade_Slide_Heading extends Heading_Widget_Base {
    public function get_name() { return 'egw-heading-minimal-fade'; }
    public function get_title() { return __('Minimal Fade Slide', 'elementor-gsap-widgets'); }
    public function get_icon() { return 'eicon-animation-text'; }

    protected function register_controls() {
        $this->register_heading_controls();
        $this->start_controls_section('section_minimal_settings', [
            'label' => __('Animation Settings', 'elementor-gsap-widgets'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        $this->add_control('slide_distance', [
            'label' => __('Slide Distance (px)', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 20],
            'range' => ['px' => ['min' => 0, 'max' => 100]],
        ]);
        $this->add_control('slide_direction', [
            'label' => __('Slide Direction', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'up',
            'options' => [
                'up' => __('Up', 'elementor-gsap-widgets'),
                'down' => __('Down', 'elementor-gsap-widgets'),
                'left' => __('Left', 'elementor-gsap-widgets'),
                'right' => __('Right', 'elementor-gsap-widgets'),
            ],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $attrs = $this->get_heading_attributes($settings, 'minimal-fade');
        $attrs['data-egw-slide-distance'] = $settings['slide_distance']['size'] ?? 20;
        $attrs['data-egw-slide-direction'] = $settings['slide_direction'] ?? 'up';
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
