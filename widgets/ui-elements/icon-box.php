<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Icon_Box_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-icon-box';
    }

    public function get_title() {
        return __('Icon Box', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-icon-box';
    }

    public function get_categories() {
        return ['egw-ui-elements'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_icon',
            [
                'label' => __('Icon', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label' => __('Icon', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Icon Box Title', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'elementor-gsap-widgets'),
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

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#667eea',
                'selectors' => [
                    '{{WRAPPER}} .egw-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_size',
            [
                'label' => __('Icon Size', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 150,
                    ],
                ],
                'default' => [
                    'size' => 60,
                ],
                'selectors' => [
                    '{{WRAPPER}} .egw-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->add_animation_controls();
        $this->add_scrolltrigger_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $anim_attrs = $this->get_animation_attributes($settings);
        $anim_attrs['data-egw-icon-box'] = 'true';

        ?>
        <div class="egw-widget egw-icon-box-widget">
            <div class="egw-icon-box-container" <?php echo $this->render_attributes_string($anim_attrs); ?>>
                <div class="egw-icon-wrapper">
                    <?php \Elementor\Icons_Manager::render_icon($settings['selected_icon'], ['class' => 'egw-icon', 'aria-hidden' => 'true']); ?>
                </div>
                <h3 class="egw-icon-box-title"><?php echo esc_html($settings['title']); ?></h3>
                <p class="egw-icon-box-description"><?php echo esc_html($settings['description']); ?></p>
            </div>
        </div>
        <?php
    }
}
