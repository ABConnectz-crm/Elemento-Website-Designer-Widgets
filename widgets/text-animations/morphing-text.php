<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Morphing_Text_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-morphing-text';
    }

    public function get_title() {
        return __('Morphing Text', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-animation';
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
            'texts',
            [
                'label' => __('Texts (one per line)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => "Innovation\nCreativity\nExcellence",
                'description' => __('Enter each text on a new line', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'morph_duration',
            [
                'label' => __('Morph Duration (seconds)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                        'step' => 0.5,
                    ],
                ],
                'default' => [
                    'size' => 2,
                ],
            ]
        );

        $this->add_control(
            'delay_between',
            [
                'label' => __('Delay Between (seconds)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0.5,
                        'max' => 5,
                        'step' => 0.5,
                    ],
                ],
                'default' => [
                    'size' => 1,
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
                'selector' => '{{WRAPPER}} .egw-morph-container',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Color', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .egw-morph-container' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $texts = array_filter(explode("\n", $settings['texts']));
        $texts_json = json_encode($texts);

        ?>
        <div class="egw-widget egw-morphing-text-widget">
            <div class="egw-morph-container"
                 data-egw-morph="true"
                 data-egw-texts='<?php echo esc_attr($texts_json); ?>'
                 data-egw-duration="<?php echo esc_attr($settings['morph_duration']['size']); ?>"
                 data-egw-delay="<?php echo esc_attr($settings['delay_between']['size']); ?>">
                <?php echo esc_html($texts[0]); ?>
            </div>
        </div>
        <script>
        (function($) {
            $(document).ready(function() {
                $('.egw-morph-container[data-egw-morph]').each(function() {
                    const $el = $(this);
                    const texts = JSON.parse($el.attr('data-egw-texts'));
                    const duration = parseFloat($el.data('egw-duration')) || 2;
                    const delay = parseFloat($el.data('egw-delay')) || 1;
                    let currentIndex = 0;

                    function morphText() {
                        currentIndex = (currentIndex + 1) % texts.length;
                        const newText = texts[currentIndex];

                        if (typeof gsap !== 'undefined') {
                            gsap.to($el[0], {
                                opacity: 0,
                                y: -20,
                                duration: duration / 2,
                                ease: 'power2.in',
                                onComplete: function() {
                                    $el.text(newText);
                                    gsap.to($el[0], {
                                        opacity: 1,
                                        y: 0,
                                        duration: duration / 2,
                                        ease: 'power2.out',
                                    });
                                }
                            });
                        } else {
                            $el.fadeOut(duration * 500, function() {
                                $el.text(newText).fadeIn(duration * 500);
                            });
                        }
                    }

                    setInterval(morphText, (duration + delay) * 1000);
                });
            });
        })(jQuery);
        </script>
        <?php
    }
}
