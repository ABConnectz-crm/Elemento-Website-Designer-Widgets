<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Staggered Text Widget
 */
class Staggered_Text_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-staggered-text';
    }

    public function get_title() {
        return __('Staggered Text', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-animation-text';
    }

    public function get_categories() {
        return ['egw-text-animations'];
    }

    public function get_script_depends() {
        return ['gsap', 'gsap-scrolltrigger', 'egw-animation-utilities'];
    }

    protected function get_animation_types() {
        return [
            'fade-in' => __('Fade In', 'elementor-gsap-widgets'),
            'slide-up' => __('Slide Up', 'elementor-gsap-widgets'),
            'slide-down' => __('Slide Down', 'elementor-gsap-widgets'),
            'slide-left' => __('Slide Left', 'elementor-gsap-widgets'),
            'slide-right' => __('Slide Right', 'elementor-gsap-widgets'),
            'scale-up' => __('Scale Up', 'elementor-gsap-widgets'),
            'rotate-in' => __('Rotate In', 'elementor-gsap-widgets'),
        ];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'text',
            [
                'label' => __('Text', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Animated Text with Stagger Effect', 'elementor-gsap-widgets'),
                'placeholder' => __('Enter your text', 'elementor-gsap-widgets'),
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
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
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
                ],
                'description' => __('How to split the text for animation', 'elementor-gsap-widgets'),
            ]
        );

        $this->end_controls_section();

        // Typography
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
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .egw-stagger-container',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .egw-stagger-container' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_align',
            [
                'label' => __('Alignment', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'elementor-gsap-widgets'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'elementor-gsap-widgets'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'elementor-gsap-widgets'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justified', 'elementor-gsap-widgets'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .egw-stagger-container' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Add common controls
        $this->add_animation_controls();
        $this->add_stagger_controls();
        $this->add_scrolltrigger_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $tag = $settings['html_tag'];

        // Get animation attributes
        $anim_attrs = $this->get_animation_attributes($settings);
        $stagger_attrs = $this->get_stagger_attributes($settings);

        // Merge attributes
        $all_attrs = array_merge($anim_attrs, $stagger_attrs);

        // Wrap text for animation
        $text = $settings['text'];
        $split_type = $settings['split_type'];

        // Add split type for JS
        $all_attrs['data-egw-split-type'] = $split_type;

        ?>
        <div class="egw-widget egw-staggered-text-widget">
            <<?php echo esc_attr($tag); ?> class="egw-stagger-container" <?php echo $this->render_attributes_string($all_attrs); ?>>
                <?php echo esc_html($text); ?>
            </<?php echo esc_attr($tag); ?>>
        </div>
        <?php
    }
}
