<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Horizontal Scroll Section Widget
 * Vertical scroll drives horizontal movement - Awwwards style
 * Uses only FREE GSAP plugins (Core + ScrollTrigger)
 */
class Horizontal_Scroll_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-horizontal-scroll';
    }

    public function get_title() {
        return __('Horizontal Scroll Section', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-h-align-stretch';
    }

    public function get_categories() {
        return ['egw-ui-elements'];
    }

    public function get_script_depends() {
        return ['gsap', 'gsap-scrolltrigger', 'egw-horizontal-scroll-handler'];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Scroll Sections', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'section_title',
            [
                'label' => __('Section Title', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Section Title', 'elementor-gsap-widgets'),
            ]
        );

        $repeater->add_control(
            'section_content',
            [
                'label' => __('Content', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => __('Section content goes here', 'elementor-gsap-widgets'),
            ]
        );

        $repeater->add_control(
            'section_background',
            [
                'label' => __('Background Color', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $repeater->add_control(
            'section_image',
            [
                'label' => __('Background Image', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'sections',
            [
                'label' => __('Sections', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'section_title' => __('Section 1', 'elementor-gsap-widgets'),
                        'section_content' => __('First section', 'elementor-gsap-widgets'),
                        'section_background' => '#f8f9fa',
                    ],
                    [
                        'section_title' => __('Section 2', 'elementor-gsap-widgets'),
                        'section_content' => __('Second section', 'elementor-gsap-widgets'),
                        'section_background' => '#e9ecef',
                    ],
                    [
                        'section_title' => __('Section 3', 'elementor-gsap-widgets'),
                        'section_content' => __('Third section', 'elementor-gsap-widgets'),
                        'section_background' => '#dee2e6',
                    ],
                ],
                'title_field' => '{{{ section_title }}}',
            ]
        );

        $this->end_controls_section();

        // Scroll Settings
        $this->start_controls_section(
            'section_scroll_settings',
            [
                'label' => __('Scroll Settings', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'scroll_speed',
            [
                'label' => __('Scroll Speed', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.5,
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'description' => __('Speed multiplier for horizontal scroll', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'smooth_scrub',
            [
                'label' => __('Smooth Scrubbing', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'description' => __('Smoothness of scroll (0 = instant, higher = more lag)', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'enable_snap',
            [
                'label' => __('Enable Snap Points', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'elementor-gsap-widgets'),
                'label_off' => __('No', 'elementor-gsap-widgets'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => __('Snap to each section', 'elementor-gsap-widgets'),
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Section Style', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'section_width',
            [
                'label' => __('Section Width', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vw', '%'],
                'default' => [
                    'size' => 100,
                    'unit' => 'vw',
                ],
                'range' => [
                    'px' => [
                        'min' => 300,
                        'max' => 2000,
                    ],
                    'vw' => [
                        'min' => 50,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .egw-h-section' => 'width: {{SIZE}}{{UNIT}}; flex-shrink: 0;',
                ],
            ]
        );

        $this->add_responsive_control(
            'section_height',
            [
                'label' => __('Section Height', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'default' => [
                    'size' => 100,
                    'unit' => 'vh',
                ],
                'range' => [
                    'px' => [
                        'min' => 400,
                        'max' => 1200,
                    ],
                    'vh' => [
                        'min' => 50,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .egw-h-scroll-wrapper' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .egw-h-section' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'section_gap',
            [
                'label' => __('Gap Between Sections (px)', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .egw-h-scroll-container' => 'gap: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $data_attrs = [
            'data-egw-scroll-speed' => $settings['scroll_speed']['size'] ?? 1,
            'data-egw-smooth-scrub' => $settings['smooth_scrub']['size'] ?? 1,
            'data-egw-enable-snap' => ($settings['enable_snap'] === 'yes') ? 'true' : 'false',
        ];

        ?>
        <div class="egw-widget egw-horizontal-scroll-widget">
            <div class="egw-h-scroll-wrapper" <?php echo $this->render_attributes_string($data_attrs); ?>>
                <div class="egw-h-scroll-container">
                    <?php foreach ($settings['sections'] as $index => $section) : ?>
                        <div class="egw-h-section" data-section-index="<?php echo $index; ?>"
                             style="background-color: <?php echo esc_attr($section['section_background']); ?>;
                                    <?php if (!empty($section['section_image']['url'])) : ?>
                                    background-image: url('<?php echo esc_url($section['section_image']['url']); ?>');
                                    background-size: cover;
                                    background-position: center;
                                    <?php endif; ?>">
                            <div class="egw-section-content">
                                <h2><?php echo esc_html($section['section_title']); ?></h2>
                                <div><?php echo wp_kses_post($section['section_content']); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }
}
