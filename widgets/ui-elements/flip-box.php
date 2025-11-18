<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Flip_Box_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-flip-box';
    }

    public function get_title() {
        return __('Flip Box', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-flip-box';
    }

    public function get_categories() {
        return ['egw-ui-elements'];
    }

    public function get_script_depends() {
        return ['gsap', 'gsap-flip', 'egw-animation-utilities'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_front',
            [
                'label' => __('Front', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'front_title',
            [
                'label' => __('Title', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Front Side', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'front_description',
            [
                'label' => __('Description', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Hover to flip', 'elementor-gsap-widgets'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_back',
            [
                'label' => __('Back', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'back_title',
            [
                'label' => __('Title', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Back Side', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'back_description',
            [
                'label' => __('Description', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('This is the back side content', 'elementor-gsap-widgets'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_settings',
            [
                'label' => __('Settings', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'flip_direction',
            [
                'label' => __('Flip Direction', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => __('Horizontal', 'elementor-gsap-widgets'),
                    'vertical' => __('Vertical', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->add_control(
            'flip_trigger',
            [
                'label' => __('Trigger', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'hover',
                'options' => [
                    'hover' => __('Hover', 'elementor-gsap-widgets'),
                    'click' => __('Click', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label' => __('Height', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 800,
                    ],
                ],
                'default' => [
                    'size' => 400,
                ],
                'selectors' => [
                    '{{WRAPPER}} .egw-flip-box-widget' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $flip_class = 'egw-flip-' . $settings['flip_direction'];

        ?>
        <div class="egw-widget egw-flip-box-widget egw-flip-box <?php echo esc_attr($flip_class); ?>"
             data-egw-flip-box="true"
             data-egw-flip-trigger="<?php echo esc_attr($settings['flip_trigger']); ?>"
             data-egw-flip-direction="<?php echo esc_attr($settings['flip_direction']); ?>">
            <div class="egw-flip-box-inner">
                <div class="egw-flip-box-front">
                    <div class="egw-flip-title"><?php echo esc_html($settings['front_title']); ?></div>
                    <div class="egw-flip-description"><?php echo esc_html($settings['front_description']); ?></div>
                </div>
                <div class="egw-flip-box-back">
                    <div class="egw-flip-title"><?php echo esc_html($settings['back_title']); ?></div>
                    <div class="egw-flip-description"><?php echo esc_html($settings['back_description']); ?></div>
                </div>
            </div>
        </div>
        <?php if ($settings['flip_trigger'] === 'click'): ?>
        <script>
        (function($) {
            $(document).ready(function() {
                $('.egw-flip-box[data-egw-flip-trigger="click"]').on('click', function() {
                    $(this).toggleClass('egw-flipped');
                });
            });
        })(jQuery);
        </script>
        <?php endif; ?>
        <?php
    }
}
