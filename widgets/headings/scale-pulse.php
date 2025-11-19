<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Scale Pulse Heading Widget
 */
class Scale_Pulse_Heading extends Heading_Widget_Base {

    public function get_name() {
        return 'egw-heading-scale-pulse';
    }

    public function get_title() {
        return __('Scale Pulse Heading', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-animation-text';
    }

    protected function register_controls() {
        $this->register_heading_controls();

        $this->start_controls_section(
            'section_pulse_settings',
            [
                'label' => __('Pulse Settings', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'scale_from',
            [
                'label' => __('Scale From', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => ['size' => 0.8],
                'range' => ['px' => ['min' => 0.5, 'max' => 2, 'step' => 0.1]],
            ]
        );

        $this->add_control(
            'bounce_strength',
            [
                'label' => __('Bounce Strength', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'back.out(1.4)',
                'options' => [
                    'elastic.out(1, 0.3)' => __('Soft Elastic', 'elementor-gsap-widgets'),
                    'back.out(1.4)' => __('Subtle Bounce', 'elementor-gsap-widgets'),
                    'back.out(2)' => __('Strong Bounce', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $attrs = $this->get_heading_attributes($settings, 'scale-pulse');
        $attrs['data-egw-scale-from'] = $settings['scale_from']['size'] ?? 0.8;
        $attrs['data-egw-bounce'] = $settings['bounce_strength'] ?? 'back.out(1.4)';

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
