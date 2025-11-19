<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Zoom_Pan_Image_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-zoom-pan-image';
    }

    public function get_title() {
        return __('Zoom & Pan Image', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-zoom-in';
    }

    public function get_categories() {
        return ['egw-image-animations'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_image',
            [
                'label' => __('Image', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => __('Choose Image', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'zoom_scale',
            [
                'label' => __('Zoom Scale', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 2,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 1.2,
                ],
            ]
        );

        $this->add_control(
            'trigger_type',
            [
                'label' => __('Trigger', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'hover',
                'options' => [
                    'hover' => __('On Hover', 'elementor-gsap-widgets'),
                    'scroll' => __('On Scroll', 'elementor-gsap-widgets'),
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

        $this->add_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .egw-zoom-pan' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Always register ScrollTrigger controls, use condition to show/hide
        $this->add_scrolltrigger_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $image_url = $settings['image']['url'];
        $zoom_scale = $settings['zoom_scale']['size'];
        $trigger_type = $settings['trigger_type'];

        $hover_class = $trigger_type === 'hover' ? 'egw-zoom-pan' : '';
        $attrs = [];

        if ($trigger_type === 'scroll') {
            $attrs = $this->get_animation_attributes($settings);
            $attrs['data-egw-zoom-scroll'] = 'true';
            $attrs['data-egw-zoom-scale'] = $zoom_scale;
        }

        ?>
        <div class="egw-widget egw-zoom-pan-widget">
            <div class="<?php echo esc_attr($hover_class); ?>" <?php echo $this->render_attributes_string($attrs); ?>>
                <img src="<?php echo esc_url($image_url); ?>"
                     <?php if ($trigger_type === 'hover'): ?>
                     style="transition: transform 0.6s ease;"
                     onmouseover="this.style.transform='scale(<?php echo esc_attr($zoom_scale); ?>)'"
                     onmouseout="this.style.transform='scale(1)'"
                     <?php endif; ?>
                     alt="">
            </div>
        </div>
        <?php
    }
}
