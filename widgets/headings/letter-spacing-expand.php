<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Letter Spacing Expand Heading Widget
 */
class Letter_Spacing_Expand_Heading extends Heading_Widget_Base {

    public function get_name() {
        return 'egw-heading-letter-expand';
    }

    public function get_title() {
        return __('Letter Spacing Expand', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-animation-text';
    }

    protected function register_controls() {
        $this->register_heading_controls();

        $this->start_controls_section(
            'section_expand_settings',
            [
                'label' => __('Expand Settings', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'initial_spacing',
            [
                'label' => __('Initial Letter Spacing (em)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => ['size' => 0.5],
                'range' => ['px' => ['min' => 0, 'max' => 2, 'step' => 0.1]],
            ]
        );

        $this->add_control(
            'final_spacing',
            [
                'label' => __('Final Letter Spacing (em)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => ['size' => 0],
                'range' => ['px' => ['min' => 0, 'max' => 1, 'step' => 0.05]],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $attrs = $this->get_heading_attributes($settings, 'letter-expand');
        $attrs['data-egw-initial-spacing'] = $settings['initial_spacing']['size'] ?? 0.5;
        $attrs['data-egw-final-spacing'] = $settings['final_spacing']['size'] ?? 0;

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
