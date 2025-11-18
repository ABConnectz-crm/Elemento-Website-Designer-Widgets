<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Timeline_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-timeline';
    }

    public function get_title() {
        return __('Timeline', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-time-line';
    }

    public function get_categories() {
        return ['egw-ui-elements'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_timeline',
            [
                'label' => __('Timeline Items', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'timeline_items',
            [
                'label' => __('Items', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'date',
                        'label' => __('Date', 'elementor-gsap-widgets'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => __('2024', 'elementor-gsap-widgets'),
                    ],
                    [
                        'name' => 'title',
                        'label' => __('Title', 'elementor-gsap-widgets'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => __('Timeline Event', 'elementor-gsap-widgets'),
                    ],
                    [
                        'name' => 'description',
                        'label' => __('Description', 'elementor-gsap-widgets'),
                        'type' => \Elementor\Controls_Manager::TEXTAREA,
                        'default' => __('Event description goes here', 'elementor-gsap-widgets'),
                    ],
                ],
                'default' => [
                    [
                        'date' => __('2020', 'elementor-gsap-widgets'),
                        'title' => __('Company Founded', 'elementor-gsap-widgets'),
                        'description' => __('Started our journey', 'elementor-gsap-widgets'),
                    ],
                    [
                        'date' => __('2022', 'elementor-gsap-widgets'),
                        'title' => __('Major Milestone', 'elementor-gsap-widgets'),
                        'description' => __('Reached new heights', 'elementor-gsap-widgets'),
                    ],
                    [
                        'date' => __('2024', 'elementor-gsap-widgets'),
                        'title' => __('Present Day', 'elementor-gsap-widgets'),
                        'description' => __('Continuing to innovate', 'elementor-gsap-widgets'),
                    ],
                ],
                'title_field' => '{{{ title }}}',
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
            'line_color',
            [
                'label' => __('Line Color', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#667eea',
                'selectors' => [
                    '{{WRAPPER}} .egw-timeline-line' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'marker_color',
            [
                'label' => __('Marker Color', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#667eea',
                'selectors' => [
                    '{{WRAPPER}} .egw-timeline-marker' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->add_animation_controls();
        $this->add_scrolltrigger_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $anim_attrs = $this->get_animation_attributes($settings);

        ?>
        <div class="egw-widget egw-timeline-widget">
            <div class="egw-timeline">
                <div class="egw-timeline-line"></div>
                <?php foreach ($settings['timeline_items'] as $index => $item): ?>
                    <div class="egw-timeline-item" <?php echo $this->render_attributes_string($anim_attrs); ?> data-egw-batch="true">
                        <div class="egw-timeline-marker"></div>
                        <div class="egw-timeline-content">
                            <div class="egw-timeline-date"><?php echo esc_html($item['date']); ?></div>
                            <h3 class="egw-timeline-title"><?php echo esc_html($item['title']); ?></h3>
                            <p class="egw-timeline-description"><?php echo esc_html($item['description']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
