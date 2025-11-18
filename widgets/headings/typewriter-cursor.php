<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Typewriter_Cursor_Heading extends Heading_Widget_Base {
    public function get_name() { return 'egw-heading-typewriter'; }
    public function get_title() { return __('Typewriter Cursor (Funky)', 'elementor-gsap-widgets'); }
    public function get_icon() { return 'eicon-animation-text'; }

    protected function register_controls() {
        $this->register_heading_controls();
        $this->start_controls_section('section_typewriter_settings', [
            'label' => __('Typewriter Settings', 'elementor-gsap-widgets'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        $this->add_control('typing_speed', [
            'label' => __('Typing Speed (seconds per char)', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 0.1],
            'range' => ['px' => ['min' => 0.01, 'max' => 0.5, 'step' => 0.01]],
        ]);
        $this->add_control('cursor_style', [
            'label' => __('Cursor Style', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'block',
            'options' => [
                'block' => __('Block', 'elementor-gsap-widgets'),
                'line' => __('Line', 'elementor-gsap-widgets'),
                'underscore' => __('Underscore', 'elementor-gsap-widgets'),
            ],
        ]);
        $this->add_control('cursor_blink', [
            'label' => __('Cursor Blink', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $attrs = $this->get_heading_attributes($settings, 'typewriter');
        $attrs['data-egw-typing-speed'] = $settings['typing_speed']['size'] ?? 0.1;
        $attrs['data-egw-cursor-style'] = $settings['cursor_style'] ?? 'block';
        $attrs['data-egw-cursor-blink'] = ($settings['cursor_blink'] === 'yes') ? 'true' : 'false';
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
