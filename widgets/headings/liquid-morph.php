<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Liquid_Morph_Heading extends Heading_Widget_Base {
    public function get_name() { return 'egw-heading-liquid-morph'; }
    public function get_title() { return __('Liquid Morph (Funky)', 'elementor-gsap-widgets'); }
    public function get_icon() { return 'eicon-animation-text'; }

    protected function register_controls() {
        $this->register_heading_controls();
        $this->start_controls_section('section_liquid_settings', [
            'label' => __('Liquid Settings', 'elementor-gsap-widgets'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        $this->add_control('morph_intensity', [
            'label' => __('Morph Intensity', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 2],
            'range' => ['px' => ['min' => 0.5, 'max' => 5, 'step' => 0.5]],
        ]);
        $this->add_control('wave_count', [
            'label' => __('Wave Count', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 3],
            'range' => ['px' => ['min' => 1, 'max' => 10]],
        ]);
        $this->add_control('liquid_speed', [
            'label' => __('Animation Speed (seconds)', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 2],
            'range' => ['px' => ['min' => 0.5, 'max' => 5, 'step' => 0.1]],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $attrs = $this->get_heading_attributes($settings, 'liquid-morph');
        $attrs['data-egw-morph-intensity'] = $settings['morph_intensity']['size'] ?? 2;
        $attrs['data-egw-wave-count'] = $settings['wave_count']['size'] ?? 3;
        $attrs['data-egw-liquid-speed'] = $settings['liquid_speed']['size'] ?? 2;
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
