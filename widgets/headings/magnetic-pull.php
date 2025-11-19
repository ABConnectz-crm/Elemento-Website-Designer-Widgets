<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Magnetic_Pull_Heading extends Heading_Widget_Base {
    public function get_name() { return 'egw-heading-magnetic'; }
    public function get_title() { return __('Magnetic Pull (Funky)', 'elementor-gsap-widgets'); }
    public function get_icon() { return 'eicon-animation-text'; }

    protected function register_controls() {
        $this->register_heading_controls();
        $this->start_controls_section('section_magnetic_settings', [
            'label' => __('Magnetic Settings', 'elementor-gsap-widgets'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        $this->add_control('pull_origin', [
            'label' => __('Pull From', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'center',
            'options' => [
                'center' => __('Center', 'elementor-gsap-widgets'),
                'left' => __('Left', 'elementor-gsap-widgets'),
                'right' => __('Right', 'elementor-gsap-widgets'),
                'top' => __('Top', 'elementor-gsap-widgets'),
                'bottom' => __('Bottom', 'elementor-gsap-widgets'),
            ],
        ]);
        $this->add_control('magnetic_strength', [
            'label' => __('Magnetic Strength', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 1],
            'range' => ['px' => ['min' => 0.5, 'max' => 3, 'step' => 0.1]],
        ]);
        $this->add_control('stagger', [
            'label' => __('Stagger (seconds)', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 0.05],
            'range' => ['px' => ['min' => 0, 'max' => 0.3, 'step' => 0.01]],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $attrs = $this->get_heading_attributes($settings, 'magnetic');
        $attrs['data-egw-pull-origin'] = $settings['pull_origin'] ?? 'center';
        $attrs['data-egw-magnetic-strength'] = $settings['magnetic_strength']['size'] ?? 1;
        $attrs['data-egw-stagger'] = $settings['stagger']['size'] ?? 0.05;
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
