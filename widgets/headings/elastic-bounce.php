<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Elastic_Bounce_Heading extends Heading_Widget_Base {
    public function get_name() { return 'egw-heading-elastic-bounce'; }
    public function get_title() { return __('Elastic Bounce (Funky)', 'elementor-gsap-widgets'); }
    public function get_icon() { return 'eicon-animation-text'; }

    protected function register_controls() {
        $this->register_heading_controls();
        $this->start_controls_section('section_bounce_settings', [
            'label' => __('Bounce Settings', 'elementor-gsap-widgets'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        $this->add_control('elastic_strength', [
            'label' => __('Elastic Strength', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'elastic.out(1, 0.5)',
            'options' => [
                'elastic.out(1, 0.3)' => __('Soft', 'elementor-gsap-widgets'),
                'elastic.out(1, 0.5)' => __('Medium', 'elementor-gsap-widgets'),
                'elastic.out(1, 0.7)' => __('Strong', 'elementor-gsap-widgets'),
                'elastic.out(1, 1)' => __('Extreme', 'elementor-gsap-widgets'),
            ],
        ]);
        $this->add_control('scale_from', [
            'label' => __('Scale From', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 0],
            'range' => ['px' => ['min' => 0, 'max' => 2, 'step' => 0.1]],
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
        $attrs = $this->get_heading_attributes($settings, 'elastic-bounce');
        $attrs['data-egw-elastic'] = $settings['elastic_strength'] ?? 'elastic.out(1, 0.5)';
        $attrs['data-egw-scale-from'] = $settings['scale_from']['size'] ?? 0;
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
