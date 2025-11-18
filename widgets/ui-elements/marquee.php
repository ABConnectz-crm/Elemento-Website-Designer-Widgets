<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Marquee_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-marquee';
    }

    public function get_title() {
        return __('Marquee', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-arrow-right';
    }

    public function get_categories() {
        return ['egw-ui-elements'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => __('Items', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'text',
                        'label' => __('Text', 'elementor-gsap-widgets'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => __('Marquee Item', 'elementor-gsap-widgets'),
                    ],
                ],
                'default' => [
                    ['text' => __('Innovation', 'elementor-gsap-widgets')],
                    ['text' => __('Creativity', 'elementor-gsap-widgets')],
                    ['text' => __('Excellence', 'elementor-gsap-widgets')],
                    ['text' => __('Quality', 'elementor-gsap-widgets')],
                ],
                'title_field' => '{{{ text }}}',
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => __('Speed (seconds)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 60,
                    ],
                ],
                'default' => [
                    'size' => 20,
                ],
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label' => __('Pause on Hover', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .egw-marquee-item',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .egw-marquee-item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $speed = $settings['speed']['size'];
        $hover_class = $settings['pause_on_hover'] === 'yes' ? 'egw-marquee' : '';

        ?>
        <div class="egw-widget egw-marquee-widget" data-egw-marquee="true">
            <div class="<?php echo esc_attr($hover_class); ?> egw-marquee-container">
                <div class="egw-marquee-content" style="animation-duration: <?php echo esc_attr($speed); ?>s;">
                    <?php foreach ($settings['items'] as $item): ?>
                        <span class="egw-marquee-item"><?php echo esc_html($item['text']); ?></span>
                    <?php endforeach; ?>
                    <?php foreach ($settings['items'] as $item): ?>
                        <span class="egw-marquee-item"><?php echo esc_html($item['text']); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }
}
