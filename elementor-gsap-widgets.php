<?php
/**
 * Plugin Name: Elementor GSAP Widgets Factory
 * Description: Advanced Elementor widget library with GSAP-powered scroll animations
 * Plugin URI: https://github.com/ABConnectz-crm/Elemento-Website-Designer-Widgets
 * Version: 1.0.0
 * Author: ABConnectz CRM
 * Author URI: https://abconnectz.com
 * Text Domain: elementor-gsap-widgets
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * License: GPL v3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Elementor tested up to: 3.18.0
 * Elementor Pro tested up to: 3.18.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Plugin constants
define('EGW_VERSION', '1.0.0');
define('EGW_FILE', __FILE__);
define('EGW_PATH', plugin_dir_path(__FILE__));
define('EGW_URL', plugin_dir_url(__FILE__));
define('EGW_ASSETS_URL', EGW_URL . 'assets/');
define('EGW_WIDGETS_PATH', EGW_PATH . 'widgets/');

/**
 * Main Plugin Class
 */
final class Elementor_GSAP_Widgets {

    /**
     * Instance
     */
    private static $_instance = null;

    /**
     * Get Instance
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    /**
     * Initialize Plugin
     */
    public function init() {
        // Check if Elementor is installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_elementor']);
            return;
        }

        // Check for minimum Elementor version
        if (!version_compare(ELEMENTOR_VERSION, '3.0.0', '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return;
        }

        // Check for minimum PHP version
        if (version_compare(PHP_VERSION, '7.4', '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return;
        }

        // Wait for Elementor to fully initialize before loading our files
        add_action('elementor/init', [$this, 'on_elementor_init']);

        // Localization
        add_action('init', [$this, 'load_textdomain']);
    }

    /**
     * On Elementor Init
     * Fires after Elementor is fully loaded and classes are available
     */
    public function on_elementor_init() {
        // Load plugin files - now Elementor classes are available
        $this->includes();

        // Register widgets
        add_action('elementor/widgets/register', [$this, 'register_widgets']);

        // Register widget categories
        add_action('elementor/elements/categories_registered', [$this, 'register_widget_categories']);

        // Enqueue scripts and styles
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'enqueue_frontend_styles']);
        add_action('elementor/frontend/after_register_scripts', [$this, 'enqueue_frontend_scripts']);

        // Editor scripts
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueue_editor_scripts']);
    }

    /**
     * Include required files
     */
    private function includes() {
        // Base widget class
        require_once EGW_PATH . 'includes/widget-base.php';
        require_once EGW_PATH . 'includes/heading-widget-base.php';
        require_once EGW_PATH . 'includes/animation-handler.php';
        require_once EGW_PATH . 'includes/helper-functions.php';
    }

    /**
     * Register widgets
     */
    public function register_widgets($widgets_manager) {
        // Text Animation Widgets
        require_once EGW_WIDGETS_PATH . 'text-animations/glassmorphism-text.php';
        require_once EGW_WIDGETS_PATH . 'text-animations/staggered-text.php';
        require_once EGW_WIDGETS_PATH . 'text-animations/split-text.php';
        require_once EGW_WIDGETS_PATH . 'text-animations/gradient-text.php';
        require_once EGW_WIDGETS_PATH . 'text-animations/typewriter-text.php';
        require_once EGW_WIDGETS_PATH . 'text-animations/morphing-text.php';

        // Advanced Text Animation Widgets (Awwwards Style)
        require_once EGW_WIDGETS_PATH . 'text-animations/kinetic-3d-text.php';
        require_once EGW_WIDGETS_PATH . 'text-animations/skew-reveal-text.php';

        // Image Animation Widgets
        require_once EGW_WIDGETS_PATH . 'image-animations/parallax-image.php';
        require_once EGW_WIDGETS_PATH . 'image-animations/masked-image.php';
        require_once EGW_WIDGETS_PATH . 'image-animations/reveal-image.php';
        require_once EGW_WIDGETS_PATH . 'image-animations/zoom-pan-image.php';

        // Background Widgets
        require_once EGW_WIDGETS_PATH . 'backgrounds/particle-bg.php';
        require_once EGW_WIDGETS_PATH . 'backgrounds/gradient-bg.php';
        require_once EGW_WIDGETS_PATH . 'backgrounds/wave-bg.php';

        // UI Element Widgets
        require_once EGW_WIDGETS_PATH . 'ui-elements/icon-box.php';
        require_once EGW_WIDGETS_PATH . 'ui-elements/flip-box.php';
        require_once EGW_WIDGETS_PATH . 'ui-elements/marquee.php';
        require_once EGW_WIDGETS_PATH . 'ui-elements/timeline.php';
        require_once EGW_WIDGETS_PATH . 'ui-elements/comparison-slider.php';

        // Advanced UI Element Widgets (Awwwards Style)
        require_once EGW_WIDGETS_PATH . 'ui-elements/advanced-clippath-reveal.php';
        require_once EGW_WIDGETS_PATH . 'ui-elements/scrollytelling-pin.php';
        require_once EGW_WIDGETS_PATH . 'ui-elements/horizontal-scroll.php';

        // Professional Heading Widgets
        require_once EGW_WIDGETS_PATH . 'headings/fade-in-stagger.php';
        require_once EGW_WIDGETS_PATH . 'headings/slide-up-reveal.php';
        require_once EGW_WIDGETS_PATH . 'headings/letter-spacing-expand.php';
        require_once EGW_WIDGETS_PATH . 'headings/blur-to-focus.php';
        require_once EGW_WIDGETS_PATH . 'headings/scale-pulse.php';
        require_once EGW_WIDGETS_PATH . 'headings/split-color-reveal.php';
        require_once EGW_WIDGETS_PATH . 'headings/underline-draw.php';
        require_once EGW_WIDGETS_PATH . 'headings/glow-pulse.php';
        require_once EGW_WIDGETS_PATH . 'headings/word-rotate-in.php';
        require_once EGW_WIDGETS_PATH . 'headings/minimal-fade-slide.php';

        // Funky Heading Widgets
        require_once EGW_WIDGETS_PATH . 'headings/elastic-bounce.php';
        require_once EGW_WIDGETS_PATH . 'headings/wave-motion.php';
        require_once EGW_WIDGETS_PATH . 'headings/scramble-text.php';
        require_once EGW_WIDGETS_PATH . 'headings/neon-flicker.php';
        require_once EGW_WIDGETS_PATH . 'headings/glitch-reveal.php';
        require_once EGW_WIDGETS_PATH . 'headings/typewriter-cursor.php';
        require_once EGW_WIDGETS_PATH . 'headings/random-scatter.php';
        require_once EGW_WIDGETS_PATH . 'headings/flip-cards.php';
        require_once EGW_WIDGETS_PATH . 'headings/magnetic-pull.php';
        require_once EGW_WIDGETS_PATH . 'headings/liquid-morph.php';

        // Register all widgets
        $widgets_manager->register(new \EGW_Widgets\Glassmorphism_Text_Widget());
        $widgets_manager->register(new \EGW_Widgets\Staggered_Text_Widget());
        $widgets_manager->register(new \EGW_Widgets\Split_Text_Widget());
        $widgets_manager->register(new \EGW_Widgets\Gradient_Text_Widget());
        $widgets_manager->register(new \EGW_Widgets\Typewriter_Text_Widget());
        $widgets_manager->register(new \EGW_Widgets\Morphing_Text_Widget());

        // Advanced text widgets
        $widgets_manager->register(new \EGW_Widgets\Kinetic_3D_Text_Widget());
        $widgets_manager->register(new \EGW_Widgets\Skew_Reveal_Text_Widget());

        $widgets_manager->register(new \EGW_Widgets\Parallax_Image_Widget());
        $widgets_manager->register(new \EGW_Widgets\Masked_Image_Widget());
        $widgets_manager->register(new \EGW_Widgets\Reveal_Image_Widget());
        $widgets_manager->register(new \EGW_Widgets\Zoom_Pan_Image_Widget());

        $widgets_manager->register(new \EGW_Widgets\Particle_Background_Widget());
        $widgets_manager->register(new \EGW_Widgets\Gradient_Background_Widget());
        $widgets_manager->register(new \EGW_Widgets\Wave_Background_Widget());

        $widgets_manager->register(new \EGW_Widgets\Icon_Box_Widget());
        $widgets_manager->register(new \EGW_Widgets\Flip_Box_Widget());
        $widgets_manager->register(new \EGW_Widgets\Marquee_Widget());
        $widgets_manager->register(new \EGW_Widgets\Timeline_Widget());
        $widgets_manager->register(new \EGW_Widgets\Comparison_Slider_Widget());

        // Advanced UI widgets
        $widgets_manager->register(new \EGW_Widgets\Advanced_ClipPath_Reveal_Widget());
        $widgets_manager->register(new \EGW_Widgets\Scrollytelling_Pin_Widget());
        $widgets_manager->register(new \EGW_Widgets\Horizontal_Scroll_Widget());

        // Professional Heading Widgets
        $widgets_manager->register(new \EGW_Widgets\Fade_In_Stagger_Heading());
        $widgets_manager->register(new \EGW_Widgets\Slide_Up_Reveal_Heading());
        $widgets_manager->register(new \EGW_Widgets\Letter_Spacing_Expand_Heading());
        $widgets_manager->register(new \EGW_Widgets\Blur_To_Focus_Heading());
        $widgets_manager->register(new \EGW_Widgets\Scale_Pulse_Heading());
        $widgets_manager->register(new \EGW_Widgets\Split_Color_Reveal_Heading());
        $widgets_manager->register(new \EGW_Widgets\Underline_Draw_Heading());
        $widgets_manager->register(new \EGW_Widgets\Glow_Pulse_Heading());
        $widgets_manager->register(new \EGW_Widgets\Word_Rotate_In_Heading());
        $widgets_manager->register(new \EGW_Widgets\Minimal_Fade_Slide_Heading());

        // Funky Heading Widgets
        $widgets_manager->register(new \EGW_Widgets\Elastic_Bounce_Heading());
        $widgets_manager->register(new \EGW_Widgets\Wave_Motion_Heading());
        $widgets_manager->register(new \EGW_Widgets\Scramble_Text_Heading());
        $widgets_manager->register(new \EGW_Widgets\Neon_Flicker_Heading());
        $widgets_manager->register(new \EGW_Widgets\Glitch_Reveal_Heading());
        $widgets_manager->register(new \EGW_Widgets\Typewriter_Cursor_Heading());
        $widgets_manager->register(new \EGW_Widgets\Random_Scatter_Heading());
        $widgets_manager->register(new \EGW_Widgets\Flip_Cards_Heading());
        $widgets_manager->register(new \EGW_Widgets\Magnetic_Pull_Heading());
        $widgets_manager->register(new \EGW_Widgets\Liquid_Morph_Heading());
    }

    /**
     * Register widget categories
     */
    public function register_widget_categories($elements_manager) {
        $elements_manager->add_category(
            'egw-text-animations',
            [
                'title' => __('GSAP Text Animations', 'elementor-gsap-widgets'),
                'icon' => 'fa fa-text-width',
            ]
        );

        $elements_manager->add_category(
            'egw-image-animations',
            [
                'title' => __('GSAP Image Animations', 'elementor-gsap-widgets'),
                'icon' => 'fa fa-image',
            ]
        );

        $elements_manager->add_category(
            'egw-backgrounds',
            [
                'title' => __('GSAP Backgrounds', 'elementor-gsap-widgets'),
                'icon' => 'fa fa-paint-brush',
            ]
        );

        $elements_manager->add_category(
            'egw-ui-elements',
            [
                'title' => __('GSAP UI Elements', 'elementor-gsap-widgets'),
                'icon' => 'fa fa-th-large',
            ]
        );
    }

    /**
     * Enqueue frontend styles
     */
    public function enqueue_frontend_styles() {
        wp_enqueue_style(
            'egw-base-animations',
            EGW_ASSETS_URL . 'css/base-animations.css',
            [],
            EGW_VERSION
        );

        wp_enqueue_style(
            'egw-widget-styles',
            EGW_ASSETS_URL . 'css/widget-styles.css',
            [],
            EGW_VERSION
        );
    }

    /**
     * Enqueue frontend scripts
     */
    public function enqueue_frontend_scripts() {
        // GSAP Core (FREE)
        wp_register_script(
            'gsap',
            'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js',
            [],
            '3.12.5',
            true
        );

        // ScrollTrigger Plugin (FREE)
        wp_register_script(
            'gsap-scrolltrigger',
            'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js',
            ['gsap'],
            '3.12.5',
            true
        );

        // Flip Plugin (FREE)
        wp_register_script(
            'gsap-flip',
            'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/Flip.min.js',
            ['gsap'],
            '3.12.5',
            true
        );

        // EGW Text Splitter (FREE alternative to SplitText)
        wp_register_script(
            'egw-text-splitter',
            EGW_ASSETS_URL . 'js/text-splitter.js',
            [],
            EGW_VERSION,
            true
        );

        // Custom animation utilities
        wp_enqueue_script(
            'egw-animation-utilities',
            EGW_ASSETS_URL . 'js/animation-utilities.js',
            ['jquery', 'gsap', 'gsap-scrolltrigger'],
            EGW_VERSION,
            true
        );

        // GSAP Config
        wp_enqueue_script(
            'egw-gsap-config',
            EGW_ASSETS_URL . 'js/gsap-config.js',
            ['jquery', 'gsap', 'gsap-scrolltrigger'],
            EGW_VERSION,
            true
        );

        // ScrollTrigger Init
        wp_enqueue_script(
            'egw-scrolltrigger-init',
            EGW_ASSETS_URL . 'js/scroll-trigger-init.js',
            ['egw-gsap-config'],
            EGW_VERSION,
            true
        );

        // Advanced Widget Handlers
        wp_register_script(
            'egw-kinetic-3d-handler',
            EGW_ASSETS_URL . 'js/widgets/kinetic-3d-handler.js',
            ['jquery', 'gsap', 'gsap-scrolltrigger', 'egw-text-splitter'],
            EGW_VERSION,
            true
        );

        wp_register_script(
            'egw-skew-reveal-handler',
            EGW_ASSETS_URL . 'js/widgets/skew-reveal-handler.js',
            ['jquery', 'gsap', 'gsap-scrolltrigger', 'egw-text-splitter'],
            EGW_VERSION,
            true
        );

        wp_register_script(
            'egw-clippath-reveal-handler',
            EGW_ASSETS_URL . 'js/widgets/clippath-reveal-handler.js',
            ['jquery', 'gsap', 'gsap-scrolltrigger'],
            EGW_VERSION,
            true
        );

        wp_register_script(
            'egw-scrollytelling-pin-handler',
            EGW_ASSETS_URL . 'js/widgets/scrollytelling-pin-handler.js',
            ['jquery', 'gsap', 'gsap-scrolltrigger'],
            EGW_VERSION,
            true
        );

        wp_register_script(
            'egw-horizontal-scroll-handler',
            EGW_ASSETS_URL . 'js/widgets/horizontal-scroll-handler.js',
            ['jquery', 'gsap', 'gsap-scrolltrigger'],
            EGW_VERSION,
            true
        );

        // Heading Animations Handler (handles all 20 heading widgets)
        wp_register_script(
            'egw-heading-animations',
            EGW_ASSETS_URL . 'js/heading-animations.js',
            ['jquery', 'gsap', 'gsap-scrolltrigger', 'egw-text-splitter'],
            EGW_VERSION,
            true
        );

        // Localize script with settings
        wp_localize_script('egw-gsap-config', 'egwSettings', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('egw-nonce'),
            'isEditor' => \Elementor\Plugin::$instance->editor->is_edit_mode(),
            'reducedMotion' => false, // Can be made dynamic from settings
            'debugMode' => false, // Can be made dynamic from settings
        ]);
    }

    /**
     * Enqueue editor scripts
     */
    public function enqueue_editor_scripts() {
        wp_enqueue_script(
            'egw-editor',
            EGW_ASSETS_URL . 'js/editor.js',
            ['jquery', 'elementor-editor'],
            EGW_VERSION,
            true
        );
    }

    /**
     * Load plugin textdomain
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'elementor-gsap-widgets',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages'
        );
    }

    /**
     * Admin notice for missing Elementor
     */
    public function admin_notice_missing_elementor() {
        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'elementor-gsap-widgets'),
            '<strong>' . esc_html__('Elementor GSAP Widgets', 'elementor-gsap-widgets') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-gsap-widgets') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice for minimum Elementor version
     */
    public function admin_notice_minimum_elementor_version() {
        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-gsap-widgets'),
            '<strong>' . esc_html__('Elementor GSAP Widgets', 'elementor-gsap-widgets') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-gsap-widgets') . '</strong>',
            '3.0.0'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice for minimum PHP version
     */
    public function admin_notice_minimum_php_version() {
        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-gsap-widgets'),
            '<strong>' . esc_html__('Elementor GSAP Widgets', 'elementor-gsap-widgets') . '</strong>',
            '<strong>' . esc_html__('PHP', 'elementor-gsap-widgets') . '</strong>',
            '7.4'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }
}

// Initialize plugin
Elementor_GSAP_Widgets::instance();
