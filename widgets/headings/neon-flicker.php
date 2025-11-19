<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Neon_Flicker_Heading extends Heading_Widget_Base {
    public function get_name() { return 'egw-heading-neon-flicker'; }
    public function get_title() { return __('Neon Flicker (Funky)', 'elementor-gsap-widgets'); }
    public function get_icon() { return 'eicon-animation-text'; }

    protected function register_controls() {
        $this->register_heading_controls();
        $this->start_controls_section('section_neon_settings', [
            'label' => __('Neon Settings', 'elementor-gsap-widgets'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        $this->add_control('neon_color', [
            'label' => __('Neon Color', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#00ff00',
        ]);
        $this->add_control('glow_intensity', [
            'label' => __('Glow Intensity (px)', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 20],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
        ]);
        $this->add_control('flicker_speed', [
            'label' => __('Flicker Speed', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'medium',
            'options' => [
                'slow' => __('Slow', 'elementor-gsap-widgets'),
                'medium' => __('Medium', 'elementor-gsap-widgets'),
                'fast' => __('Fast', 'elementor-gsap-widgets'),
            ],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $attrs = $this->get_heading_attributes($settings, 'neon-flicker');
        $attrs['data-egw-neon-color'] = $settings['neon_color'] ?? '#00ff00';
        $attrs['data-egw-glow-intensity'] = $settings['glow_intensity']['size'] ?? 20;
        $attrs['data-egw-flicker-speed'] = $settings['flicker_speed'] ?? 'medium';
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
