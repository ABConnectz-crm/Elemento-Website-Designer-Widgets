<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Random_Scatter_Heading extends Heading_Widget_Base {
    public function get_name() { return 'egw-heading-scatter'; }
    public function get_title() { return __('Random Scatter (Funky)', 'elementor-gsap-widgets'); }
    public function get_icon() { return 'eicon-animation-text'; }

    protected function register_controls() {
        $this->register_heading_controls();
        $this->start_controls_section('section_scatter_settings', [
            'label' => __('Scatter Settings', 'elementor-gsap-widgets'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        $this->add_control('scatter_distance', [
            'label' => __('Scatter Distance (px)', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 200],
            'range' => ['px' => ['min' => 50, 'max' => 500]],
        ]);
        $this->add_control('rotation_range', [
            'label' => __('Rotation Range (degrees)', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 180],
            'range' => ['px' => ['min' => 0, 'max' => 360]],
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
        $attrs = $this->get_heading_attributes($settings, 'scatter');
        $attrs['data-egw-scatter-distance'] = $settings['scatter_distance']['size'] ?? 200;
        $attrs['data-egw-rotation-range'] = $settings['rotation_range']['size'] ?? 180;
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
