<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Masked_Image_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-masked-image';
    }

    public function get_title() {
        return __('Masked Image', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-image-rollover';
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
            'mask_type',
            [
                'label' => __('Mask Type', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'circle',
                'options' => [
                    'circle' => __('Circle', 'elementor-gsap-widgets'),
                    'polygon' => __('Polygon', 'elementor-gsap-widgets'),
                    'blob' => __('Blob', 'elementor-gsap-widgets'),
                ],
            ]
        );

        $this->add_control(
            'animate_mask',
            [
                'label' => __('Animate Mask', 'elementor-gsap-widgets'),
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

        $this->add_responsive_control(
            'width',
            [
                'label' => __('Width', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 600,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .egw-masked-image' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->add_animation_controls();
        $this->add_scrolltrigger_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $image_url = $settings['image']['url'];
        $mask_type = $settings['mask_type'];

        $clip_path_styles = [
            'circle' => 'clip-path: circle(50% at 50% 50%);',
            'polygon' => 'clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);',
            'blob' => 'clip-path: url(#blob-mask);',
        ];

        $inline_style = isset($clip_path_styles[$mask_type]) ? $clip_path_styles[$mask_type] : '';
        $anim_attrs = $this->get_animation_attributes($settings);

        // Add aspect ratio container style for circle to prevent cropping
        $container_style = $mask_type === 'circle' ? 'aspect-ratio: 1/1; max-width: 100%;' : '';

        ?>
        <div class="egw-widget egw-masked-image-widget">
            <div class="egw-masked-image" style="<?php echo esc_attr($container_style); ?>" <?php echo $this->render_attributes_string($anim_attrs); ?>>
                <img src="<?php echo esc_url($image_url); ?>"
                     style="<?php echo esc_attr($inline_style); ?> width: 100%; height: 100%; object-fit: cover;"
                     alt="">
            </div>
        </div>
        <?php if ($mask_type === 'blob'): ?>
        <svg width="0" height="0">
            <defs>
                <clipPath id="blob-mask" clipPathUnits="objectBoundingBox">
                    <path d="M0.5,0.1 C0.7,0.1,0.9,0.3,0.9,0.5 C0.9,0.7,0.7,0.9,0.5,0.9 C0.3,0.9,0.1,0.7,0.1,0.5 C0.1,0.3,0.3,0.1,0.5,0.1 Z"/>
                </clipPath>
            </defs>
        </svg>
        <?php endif; ?>
        <?php
    }
}
