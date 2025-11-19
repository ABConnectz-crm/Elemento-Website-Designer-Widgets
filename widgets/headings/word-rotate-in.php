<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Word_Rotate_In_Heading extends Heading_Widget_Base {
    public function get_name() { return 'egw-heading-word-rotate'; }
    public function get_title() { return __('Word Rotate In Heading', 'elementor-gsap-widgets'); }
    public function get_icon() { return 'eicon-animation-text'; }

    protected function register_controls() {
        $this->register_heading_controls();
        $this->start_controls_section('section_rotate_settings', [
            'label' => __('Rotate Settings', 'elementor-gsap-widgets'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        $this->add_control('rotation_degree', [
            'label' => __('Rotation Degree', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 90],
            'range' => ['px' => ['min' => 0, 'max' => 180]],
        ]);
        $this->add_control('stagger', [
            'label' => __('Stagger (seconds)', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 0.08],
            'range' => ['px' => ['min' => 0, 'max' => 0.5, 'step' => 0.01]],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $attrs = $this->get_heading_attributes($settings, 'word-rotate');
        $attrs['data-egw-rotation'] = $settings['rotation_degree']['size'] ?? 90;
        $attrs['data-egw-stagger'] = $settings['stagger']['size'] ?? 0.08;
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
