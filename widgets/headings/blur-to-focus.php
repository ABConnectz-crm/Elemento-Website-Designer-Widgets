<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Blur to Focus Heading Widget
 */
class Blur_To_Focus_Heading extends Heading_Widget_Base {

    public function get_name() {
        return 'egw-heading-blur-focus';
    }

    public function get_title() {
        return __('Blur to Focus Heading', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-animation-text';
    }

    protected function register_controls() {
        $this->register_heading_controls();

        $this->start_controls_section(
            'section_blur_settings',
            [
                'label' => __('Blur Settings', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'blur_amount',
            [
                'label' => __('Initial Blur (px)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => ['size' => 20],
                'range' => ['px' => ['min' => 0, 'max' => 50]],
            ]
        );

        $this->add_control(
            'split_type',
            [
                'label' => __('Split By', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'words',
                'options' => [
                    'none' => __('No Split (Whole)', 'elementor-gsap-widgets'),
                    'words' => __('Words', 'elementor-gsap-widgets'),
                    'chars' => __('Characters', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->add_control(
            'stagger',
            [
                'label' => __('Stagger (seconds)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => ['size' => 0.05],
                'range' => ['px' => ['min' => 0, 'max' => 0.5, 'step' => 0.01]],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $attrs = $this->get_heading_attributes($settings, 'blur-focus');
        $attrs['data-egw-blur-amount'] = $settings['blur_amount']['size'] ?? 20;
        $attrs['data-egw-split-type'] = $settings['split_type'] ?? 'words';
        $attrs['data-egw-stagger'] = $settings['stagger']['size'] ?? 0.05;

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
