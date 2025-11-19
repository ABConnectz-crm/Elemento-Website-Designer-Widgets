<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Gradient_Text_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-gradient-text';
    }

    public function get_title() {
        return __('Gradient Text', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-text-area';
    }

    public function get_categories() {
        return ['egw-text-animations'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'text',
            [
                'label' => __('Text', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Gradient Text', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'html_tag',
            [
                'label' => __('HTML Tag', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'div' => 'div',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_gradient',
            [
                'label' => __('Gradient', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gradient_color_1',
            [
                'label' => __('Color 1', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#667eea',
            ]
        );

        $this->add_control(
            'gradient_color_2',
            [
                'label' => __('Color 2', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#764ba2',
            ]
        );

        $this->add_control(
            'gradient_angle',
            [
                'label' => __('Angle', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 360,
                    ],
                ],
                'default' => [
                    'size' => 45,
                ],
            ]
        );

        $this->add_control(
            'animate_gradient',
            [
                'label' => __('Animate Gradient', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_typography',
            [
                'label' => __('Typography', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .egw-gradient-text-content',
            ]
        );

        $this->end_controls_section();

        $this->add_animation_controls();
        $this->add_scrolltrigger_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $tag = $settings['html_tag'];
        $gradient = sprintf(
            'linear-gradient(%ddeg, %s 0%%, %s 100%%)',
            $settings['gradient_angle']['size'],
            $settings['gradient_color_1'],
            $settings['gradient_color_2']
        );
        $anim_attrs = $this->get_animation_attributes($settings);
        $animate_class = $settings['animate_gradient'] === 'yes' ? 'egw-gradient-text-animated' : '';

        ?>
        <div class="egw-widget egw-gradient-text-widget">
            <<?php echo esc_attr($tag); ?> class="egw-gradient-text-content egw-gradient-text <?php echo esc_attr($animate_class); ?>" style="background: <?php echo esc_attr($gradient); ?>; -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; color: transparent;" <?php echo $this->render_attributes_string($anim_attrs); ?>>
                <?php echo esc_html($settings['text']); ?>
            </<?php echo esc_attr($tag); ?>>
        </div>
        <?php
    }
}
