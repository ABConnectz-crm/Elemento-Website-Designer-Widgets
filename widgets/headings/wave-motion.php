<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Wave_Motion_Heading extends Heading_Widget_Base {
    public function get_name() { return 'egw-heading-wave-motion'; }
    public function get_title() { return __('Wave Motion (Funky)', 'elementor-gsap-widgets'); }
    public function get_icon() { return 'eicon-animation-text'; }

    protected function register_controls() {
        $this->register_heading_controls();
        $this->start_controls_section('section_wave_settings', [
            'label' => __('Wave Settings', 'elementor-gsap-widgets'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        $this->add_control('wave_height', [
            'label' => __('Wave Height (px)', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 30],
            'range' => ['px' => ['min' => 10, 'max' => 100]],
        ]);
        $this->add_control('wave_duration', [
            'label' => __('Wave Duration (seconds)', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 1.5],
            'range' => ['px' => ['min' => 0.5, 'max' => 5, 'step' => 0.1]],
        ]);
        $this->add_control('stagger_amount', [
            'label' => __('Stagger Between Characters', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 0.03],
            'range' => ['px' => ['min' => 0.01, 'max' => 0.2, 'step' => 0.01]],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $attrs = $this->get_heading_attributes($settings, 'wave-motion');
        $attrs['data-egw-wave-height'] = $settings['wave_height']['size'] ?? 30;
        $attrs['data-egw-wave-duration'] = $settings['wave_duration']['size'] ?? 1.5;
        $attrs['data-egw-stagger'] = $settings['stagger_amount']['size'] ?? 0.03;
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
