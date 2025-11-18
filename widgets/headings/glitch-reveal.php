<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Glitch_Reveal_Heading extends Heading_Widget_Base {
    public function get_name() { return 'egw-heading-glitch'; }
    public function get_title() { return __('Glitch Reveal (Funky)', 'elementor-gsap-widgets'); }
    public function get_icon() { return 'eicon-animation-text'; }

    protected function register_controls() {
        $this->register_heading_controls();
        $this->start_controls_section('section_glitch_settings', [
            'label' => __('Glitch Settings', 'elementor-gsap-widgets'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        $this->add_control('glitch_intensity', [
            'label' => __('Glitch Intensity', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 10],
            'range' => ['px' => ['min' => 1, 'max' => 50]],
        ]);
        $this->add_control('glitch_layers', [
            'label' => __('Glitch Layers', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => '2',
            'options' => [
                '1' => __('Single', 'elementor-gsap-widgets'),
                '2' => __('Double', 'elementor-gsap-widgets'),
                '3' => __('Triple', 'elementor-gsap-widgets'),
            ],
        ]);
        $this->add_control('color_shift', [
            'label' => __('RGB Color Shift', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $attrs = $this->get_heading_attributes($settings, 'glitch');
        $attrs['data-egw-glitch-intensity'] = $settings['glitch_intensity']['size'] ?? 10;
        $attrs['data-egw-glitch-layers'] = $settings['glitch_layers'] ?? '2';
        $attrs['data-egw-color-shift'] = ($settings['color_shift'] === 'yes') ? 'true' : 'false';
        ?>
        <div class="egw-widget egw-heading-widget egw-funky-heading">
            <div class="egw-heading-container">
                <<?php echo esc_attr($settings['heading_tag']); ?> class="egw-animated-heading" <?php echo $this->render_attributes_string($attrs); ?>>
                    <?php echo esc_html($settings['heading_text']); ?>
                </<?php echo esc_attr($settings['heading_tag']); ?>>
            </div>
        </div>
        <?php
    }
}
