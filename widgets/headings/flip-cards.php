<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Flip_Cards_Heading extends Heading_Widget_Base {
    public function get_name() { return 'egw-heading-flip-cards'; }
    public function get_title() { return __('Flip Cards (Funky)', 'elementor-gsap-widgets'); }
    public function get_icon() { return 'eicon-animation-text'; }

    protected function register_controls() {
        $this->register_heading_controls();
        $this->start_controls_section('section_flip_settings', [
            'label' => __('Flip Settings', 'elementor-gsap-widgets'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        $this->add_control('flip_axis', [
            'label' => __('Flip Axis', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'y',
            'options' => [
                'x' => __('Horizontal', 'elementor-gsap-widgets'),
                'y' => __('Vertical', 'elementor-gsap-widgets'),
            ],
        ]);
        $this->add_control('back_color', [
            'label' => __('Back Side Color', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ff0000',
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
        $attrs = $this->get_heading_attributes($settings, 'flip-cards');
        $attrs['data-egw-flip-axis'] = $settings['flip_axis'] ?? 'y';
        $attrs['data-egw-back-color'] = $settings['back_color'] ?? '#ff0000';
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
