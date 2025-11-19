<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Scramble_Text_Heading extends Heading_Widget_Base {
    public function get_name() { return 'egw-heading-scramble'; }
    public function get_title() { return __('Scramble Text (Funky)', 'elementor-gsap-widgets'); }
    public function get_icon() { return 'eicon-animation-text'; }

    protected function register_controls() {
        $this->register_heading_controls();
        $this->start_controls_section('section_scramble_settings', [
            'label' => __('Scramble Settings', 'elementor-gsap-widgets'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        $this->add_control('scramble_duration', [
            'label' => __('Scramble Duration (seconds)', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 1],
            'range' => ['px' => ['min' => 0.5, 'max' => 3, 'step' => 0.1]],
        ]);
        $this->add_control('character_set', [
            'label' => __('Character Set', 'elementor-gsap-widgets'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'alphanumeric',
            'options' => [
                'alphanumeric' => __('Letters & Numbers', 'elementor-gsap-widgets'),
                'letters' => __('Letters Only', 'elementor-gsap-widgets'),
                'numbers' => __('Numbers Only', 'elementor-gsap-widgets'),
                'symbols' => __('Symbols', 'elementor-gsap-widgets'),
            ],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $attrs = $this->get_heading_attributes($settings, 'scramble');
        $attrs['data-egw-scramble-duration'] = $settings['scramble_duration']['size'] ?? 1;
        $attrs['data-egw-charset'] = $settings['character_set'] ?? 'alphanumeric';
        ?>
        <div class="egw-widget egw-heading-widget egw-funky-heading">
            <div class="egw-heading-container">
                <<?php echo esc_attr($settings['heading_tag']); ?> class="egw-animated-heading" <?php echo $this->render_attributes_string($attrs); ?>>
                    <?php echo esc_html($settings['heading_text']); ?>
                </<?php echo esc_attr($settings['heading_tag']); ?>>
            </div>
        </div>
        <?php
    }
}
