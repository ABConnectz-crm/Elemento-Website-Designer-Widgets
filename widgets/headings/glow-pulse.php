<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Glow_Pulse_Heading extends Heading_Widget_Base {
    public function get_name() { return 'egw-heading-glow-pulse'; }
    public function get_title() { return __('Glow Pulse Heading', 'elementor-gsap-widgets'); }
    public function get_icon() { return 'eicon-animation-text'; }

    protected function register_controls() {
        $this->register_heading_controls();
        $this->start_controls_section('section_glow_settings', [
            'label' => __('Glow Settings', 'elementor-gsap-widgets'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        $this->add_control('glow_color', [
            'label' => __('Glow Color', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
        ]);
        $this->add_control('glow_intensity', [
            'label' => __('Glow Intensity (px)', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 20],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $attrs = $this->get_heading_attributes($settings, 'glow-pulse');
        $attrs['data-egw-glow-color'] = $settings['glow_color'] ?? '#ffffff';
        $attrs['data-egw-glow-intensity'] = $settings['glow_intensity']['size'] ?? 20;
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
