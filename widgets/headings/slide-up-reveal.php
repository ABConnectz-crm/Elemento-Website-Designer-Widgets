<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Slide Up Reveal Heading Widget
 * Clean professional slide up animation
 */
class Slide_Up_Reveal_Heading extends Heading_Widget_Base {

    public function get_name() {
        return 'egw-heading-slide-up';
    }

    public function get_title() {
        return __('Slide Up Reveal Heading', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-animation-text';
    }

    protected function register_controls() {
        $this->register_heading_controls();

        $this->start_controls_section(
            'section_slide_settings',
            [
                'label' => __('Slide Settings', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'split_type',
            [
                'label' => __('Split By', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'lines',
                'options' => [
                    'lines' => __('Lines', 'elementor-gsap-widgets'),
                    'words' => __('Words', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->add_control(
            'slide_distance',
            [
                'label' => __('Slide Distance (px)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => ['size' => 50],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 200,
                    ],
                ],
            ]
        );

        $this->add_control(
            'stagger',
            [
                'label' => __('Stagger (seconds)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => ['size' => 0.1],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
            ]
        );

        $this->add_control(
            'use_clip_mask',
            [
                'label' => __('Use Clip Mask (Clean)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $tag = $settings['heading_tag'];

        $attrs = $this->get_heading_attributes($settings, 'slide-up');
        $attrs['data-egw-split-type'] = $settings['split_type'] ?? 'lines';
        $attrs['data-egw-slide-distance'] = $settings['slide_distance']['size'] ?? 50;
        $attrs['data-egw-stagger'] = $settings['stagger']['size'] ?? 0.1;
        $attrs['data-egw-use-clip'] = ($settings['use_clip_mask'] === 'yes') ? 'true' : 'false';

        ?>
        <div class="egw-widget egw-heading-widget egw-heading-slide-up">
            <div class="egw-heading-container">
                <<?php echo esc_attr($tag); ?> class="egw-animated-heading" <?php echo $this->render_attributes_string($attrs); ?>>
                    <?php echo esc_html($settings['heading_text']); ?>
                </<?php echo esc_attr($tag); ?>>
            </div>
        </div>
        <?php
    }
}
