<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Split Text Widget (Requires GSAP SplitText Plugin)
 */
class Split_Text_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-split-text';
    }

    public function get_title() {
        return __('Split Text', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-divider-shape';
    }

    public function get_categories() {
        return ['egw-text-animations'];
    }

    public function get_script_depends() {
        return ['gsap', 'gsap-scrolltrigger', 'gsap-splittext', 'egw-animation-utilities'];
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
                'default' => __('Split Text Animation', 'elementor-gsap-widgets'),
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
                    'h4' => 'H4',
                    'div' => 'div',
                    'p' => 'p',
                ],
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
                'selector' => '{{WRAPPER}} .egw-split-container',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Color', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .egw-split-container' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->add_animation_controls();
        $this->add_stagger_controls();
        $this->add_scrolltrigger_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $tag = $settings['html_tag'];
        $anim_attrs = $this->get_animation_attributes($settings);
        $stagger_attrs = $this->get_stagger_attributes($settings);
        $all_attrs = array_merge($anim_attrs, $stagger_attrs);
        $all_attrs['data-egw-split-text'] = 'true';

        ?>
        <div class="egw-widget egw-split-text-widget">
            <<?php echo esc_attr($tag); ?> class="egw-split-container" <?php echo $this->render_attributes_string($all_attrs); ?>>
                <?php echo esc_html($settings['text']); ?>
            </<?php echo esc_attr($tag); ?>>
        </div>
        <?php
    }
}
