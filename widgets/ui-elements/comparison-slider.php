<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Comparison_Slider_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-comparison-slider';
    }

    public function get_title() {
        return __('Comparison Slider', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-h-align-stretch';
    }

    public function get_categories() {
        return ['egw-ui-elements'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_images',
            [
                'label' => __('Images', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'before_image',
            [
                'label' => __('Before Image', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'after_image',
            [
                'label' => __('After Image', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'initial_position',
            [
                'label' => __('Initial Position (%)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 50,
                ],
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

        $this->add_responsive_control(
            'height',
            [
                'label' => __('Height', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 500,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .egw-comparison-slider-widget' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .egw-comparison-slider-widget' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_id = 'egw-comparison-' . $this->get_id();
        $before_image = $settings['before_image']['url'];
        $after_image = $settings['after_image']['url'];
        $initial_position = $settings['initial_position']['size'];

        ?>
        <div id="<?php echo esc_attr($widget_id); ?>" class="egw-widget egw-comparison-slider-widget egw-comparison-slider">
            <div class="egw-comparison-after">
                <img src="<?php echo esc_url($after_image); ?>" alt="After">
            </div>
            <div class="egw-comparison-before" style="clip-path: inset(0 <?php echo esc_attr(100 - $initial_position); ?>% 0 0);">
                <img src="<?php echo esc_url($before_image); ?>" alt="Before">
            </div>
            <div class="egw-comparison-divider" style="left: <?php echo esc_attr($initial_position); ?>%;">
                <div class="egw-comparison-handle"></div>
            </div>
        </div>
        <script>
        (function() {
            const slider = document.getElementById('<?php echo esc_js($widget_id); ?>');
            if (!slider) return;

            const before = slider.querySelector('.egw-comparison-before');
            const divider = slider.querySelector('.egw-comparison-divider');
            let isDragging = false;

            function updatePosition(x) {
                const rect = slider.getBoundingClientRect();
                const position = ((x - rect.left) / rect.width) * 100;
                const clampedPosition = Math.max(0, Math.min(100, position));

                before.style.clipPath = `inset(0 ${100 - clampedPosition}% 0 0)`;
                divider.style.left = `${clampedPosition}%`;
            }

            divider.addEventListener('mousedown', () => {
                isDragging = true;
            });

            document.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                updatePosition(e.clientX);
            });

            document.addEventListener('mouseup', () => {
                isDragging = false;
            });

            slider.addEventListener('click', (e) => {
                if (e.target === slider || e.target.tagName === 'IMG') {
                    updatePosition(e.clientX);
                }
            });

            // Touch support
            divider.addEventListener('touchstart', () => {
                isDragging = true;
            });

            document.addEventListener('touchmove', (e) => {
                if (!isDragging) return;
                updatePosition(e.touches[0].clientX);
            });

            document.addEventListener('touchend', () => {
                isDragging = false;
            });
        })();
        </script>
        <?php
    }
}
