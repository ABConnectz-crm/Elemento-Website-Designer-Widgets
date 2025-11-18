<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Split_Color_Reveal_Heading extends Heading_Widget_Base {
    public function get_name() { return 'egw-heading-split-color'; }
    public function get_title() { return __('Split Color Reveal', 'elementor-gsap-widgets'); }
    public function get_icon() { return 'eicon-animation-text'; }

    protected function register_controls() {
        $this->register_heading_controls();
        $this->start_controls_section('section_color_settings', [
            'label' => __('Color Settings', 'elementor-gsap-widgets'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        $this->add_control('initial_color', [
            'label' => __('Initial Color', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#cccccc',
        ]);
        $this->add_control('reveal_from', [
            'label' => __('Reveal From', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'left',
            'options' => [
                'left' => __('Left', 'elementor-gsap-widgets'),
                'right' => __('Right', 'elementor-gsap-widgets'),
                'center' => __('Center', 'elementor-gsap-widgets'),
            ],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $attrs = $this->get_heading_attributes($settings, 'split-color');
        $attrs['data-egw-initial-color'] = $settings['initial_color'] ?? '#cccccc';
        $attrs['data-egw-reveal-from'] = $settings['reveal_from'] ?? 'left';
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
