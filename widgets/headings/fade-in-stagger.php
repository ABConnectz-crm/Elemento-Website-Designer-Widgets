<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Fade In Stagger Heading Widget
 * Professional elegant fade with stagger effect
 */
class Fade_In_Stagger_Heading extends Heading_Widget_Base {

    public function get_name() {
        return 'egw-heading-fade-stagger';
    }

    public function get_title() {
        return __('Fade In Stagger Heading', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-animation-text';
    }

    protected function register_controls() {
        $this->register_heading_controls();

        // Animation Settings
        $this->start_controls_section(
            'section_fade_settings',
            [
                'label' => __('Fade Settings', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'split_type',
            [
                'label' => __('Split By', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'words',
                'options' => [
                    'words' => __('Words', 'elementor-gsap-widgets'),
                    'chars' => __('Characters', 'elementor-gsap-widgets'),
                    'lines' => __('Lines', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->add_control(
            'stagger_amount',
            [
                'label' => __('Stagger Amount (seconds)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => ['size' => 0.05],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 0.5,
                        'step' => 0.01,
                    ],
                ],
            ]
        );

        $this->add_control(
            'stagger_from',
            [
                'label' => __('Stagger From', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'start',
                'options' => [
                    'start' => __('Start', 'elementor-gsap-widgets'),
                    'center' => __('Center', 'elementor-gsap-widgets'),
                    'end' => __('End', 'elementor-gsap-widgets'),
                    'edges' => __('Edges', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $tag = $settings['heading_tag'];

        $attrs = $this->get_heading_attributes($settings, 'fade-in-stagger');
        $attrs['data-egw-split-type'] = $settings['split_type'] ?? 'words';
        $attrs['data-egw-stagger'] = $settings['stagger_amount']['size'] ?? 0.05;
        $attrs['data-egw-stagger-from'] = $settings['stagger_from'] ?? 'start';

        ?>
        <div class="egw-widget egw-heading-widget egw-heading-fade-stagger">
            <div class="egw-heading-container">
                <<?php echo esc_attr($tag); ?> class="egw-animated-heading" <?php echo $this->render_attributes_string($attrs); ?>>
                    <?php echo esc_html($settings['heading_text']); ?>
                </<?php echo esc_attr($tag); ?>>
            </div>
        </div>
        <?php
    }
}
