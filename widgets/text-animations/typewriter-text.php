<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Typewriter_Text_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-typewriter-text';
    }

    public function get_title() {
        return __('Typewriter Text', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-editor-code';
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
                'default' => __('Typewriter effect...', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'typing_speed',
            [
                'label' => __('Typing Speed (ms)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 500,
                    ],
                ],
                'default' => [
                    'size' => 100,
                ],
            ]
        );

        $this->add_control(
            'show_cursor',
            [
                'label' => __('Show Cursor', 'elementor-gsap-widgets'),
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
                'selector' => '{{WRAPPER}} .egw-typewriter-text',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Color', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .egw-typewriter-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
        $this->add_scrolltrigger_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $cursor_class = $settings['show_cursor'] === 'yes' ? 'egw-typewriter' : '';

        ?>
        <div class="egw-widget egw-typewriter-widget">
            <div class="egw-typewriter-text <?php echo esc_attr($cursor_class); ?>"
                 data-egw-typewriter="true"
                 data-egw-text="<?php echo esc_attr($settings['text']); ?>"
                 data-egw-speed="<?php echo esc_attr($settings['typing_speed']['size']); ?>">
            </div>
        </div>
        <script>
        (function($) {
            $(document).ready(function() {
                $('.egw-typewriter-text[data-egw-typewriter]').each(function() {
                    const $el = $(this);
                    const text = $el.data('egw-text');
                    const speed = $el.data('egw-speed') || 100;
                    let index = 0;

                    function type() {
                        if (index < text.length) {
                            $el.text($el.text() + text.charAt(index));
                            index++;
                            setTimeout(type, speed);
                        }
                    }

                    // Start typing when in viewport
                    if (typeof ScrollTrigger !== 'undefined') {
                        ScrollTrigger.create({
                            trigger: $el[0],
                            start: 'top 80%',
                            onEnter: type,
                            once: true,
                        });
                    } else {
                        type();
                    }
                });
            });
        })(jQuery);
        </script>
        <?php
    }
}
