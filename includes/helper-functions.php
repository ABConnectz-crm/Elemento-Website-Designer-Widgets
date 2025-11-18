<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Helper Functions for Elementor GSAP Widgets
 */

/**
 * Get SVG icon by name
 */
function egw_get_icon($icon_name, $class = '') {
    $icons = [
        'arrow-right' => '<svg class="' . esc_attr($class) . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
        'check' => '<svg class="' . esc_attr($class) . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>',
        'star' => '<svg class="' . esc_attr($class) . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>',
    ];

    return isset($icons[$icon_name]) ? $icons[$icon_name] : '';
}

/**
 * Sanitize HTML class
 */
function egw_sanitize_html_class($class) {
    return sanitize_html_class($class);
}

/**
 * Check if we're in Elementor editor mode
 */
function egw_is_editor_mode() {
    return \Elementor\Plugin::$instance->editor->is_edit_mode();
}

/**
 * Check if we're in Elementor preview mode
 */
function egw_is_preview_mode() {
    return \Elementor\Plugin::$instance->preview->is_preview_mode();
}

/**
 * Get responsive control value
 */
function egw_get_responsive_value($settings, $control_name, $device = 'desktop') {
    $value = isset($settings[$control_name]) ? $settings[$control_name] : '';

    if ($device !== 'desktop') {
        $device_value = isset($settings[$control_name . '_' . $device]) ? $settings[$control_name . '_' . $device] : '';
        if (!empty($device_value)) {
            $value = $device_value;
        }
    }

    return $value;
}

/**
 * Generate gradient CSS
 */
function egw_generate_gradient_css($type, $colors, $angle = 45) {
    if (empty($colors) || !is_array($colors)) {
        return '';
    }

    $color_stops = [];
    foreach ($colors as $color) {
        $color_stop = isset($color['color']) ? $color['color'] : '#000';
        if (isset($color['position'])) {
            $color_stop .= ' ' . $color['position'] . '%';
        }
        $color_stops[] = $color_stop;
    }

    $gradient_string = implode(', ', $color_stops);

    if ($type === 'linear') {
        return sprintf('linear-gradient(%deg, %s)', $angle, $gradient_string);
    } elseif ($type === 'radial') {
        return sprintf('radial-gradient(circle, %s)', $gradient_string);
    }

    return '';
}

/**
 * Get image URL from settings
 */
function egw_get_image_url($image_setting) {
    if (empty($image_setting)) {
        return '';
    }

    if (is_array($image_setting) && isset($image_setting['url'])) {
        return $image_setting['url'];
    }

    return '';
}

/**
 * Get attachment image by ID with size
 */
function egw_get_attachment_image($attachment_id, $size = 'full') {
    if (empty($attachment_id)) {
        return '';
    }

    $image = wp_get_attachment_image_src($attachment_id, $size);

    if ($image) {
        return $image[0];
    }

    return '';
}

/**
 * Parse text for word/character splitting
 */
function egw_parse_text_for_splitting($text, $split_by = 'words') {
    if ($split_by === 'words') {
        // Split by words, preserving spaces
        $words = preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY);
        return $words;
    } elseif ($split_by === 'chars') {
        // Split by characters
        return str_split($text);
    } elseif ($split_by === 'lines') {
        // Split by lines
        return explode("\n", $text);
    }

    return [$text];
}

/**
 * Wrap text elements for animation
 */
function egw_wrap_text_for_animation($text, $split_by = 'words', $wrapper_class = 'egw-anim-item') {
    $elements = egw_parse_text_for_splitting($text, $split_by);
    $wrapped = [];

    foreach ($elements as $element) {
        $wrapped[] = sprintf('<span class="%s">%s</span>', esc_attr($wrapper_class), esc_html($element));
    }

    if ($split_by === 'words') {
        return implode(' ', $wrapped);
    } elseif ($split_by === 'lines') {
        return implode('<br>', $wrapped);
    }

    return implode('', $wrapped);
}

/**
 * Get video embed URL
 */
function egw_get_video_embed_url($url) {
    if (empty($url)) {
        return '';
    }

    // YouTube
    if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $url, $id)) {
        return 'https://www.youtube.com/embed/' . $id[1] . '?autoplay=1&mute=1&controls=0&loop=1';
    } elseif (preg_match('/youtu\.be\/([^\&\?\/]+)/', $url, $id)) {
        return 'https://www.youtube.com/embed/' . $id[1] . '?autoplay=1&mute=1&controls=0&loop=1';
    }

    // Vimeo
    if (preg_match('/vimeo\.com\/([0-9]+)/', $url, $id)) {
        return 'https://player.vimeo.com/video/' . $id[1] . '?autoplay=1&muted=1&controls=0&loop=1';
    }

    return $url;
}

/**
 * Generate responsive breakpoint attributes
 */
function egw_generate_breakpoint_data($settings, $control_name) {
    $data = [];

    $data['desktop'] = isset($settings[$control_name]) ? $settings[$control_name] : '';
    $data['tablet'] = isset($settings[$control_name . '_tablet']) ? $settings[$control_name . '_tablet'] : $data['desktop'];
    $data['mobile'] = isset($settings[$control_name . '_mobile']) ? $settings[$control_name . '_mobile'] : $data['tablet'];

    return [
        'data-' . $control_name . '-desktop' => $data['desktop'],
        'data-' . $control_name . '-tablet' => $data['tablet'],
        'data-' . $control_name . '-mobile' => $data['mobile'],
    ];
}

/**
 * Convert HEX color to RGBA
 */
function egw_hex_to_rgba($hex, $alpha = 1) {
    $hex = str_replace('#', '', $hex);

    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }

    return sprintf('rgba(%d, %d, %d, %s)', $r, $g, $b, $alpha);
}

/**
 * Clamp value between min and max
 */
function egw_clamp($value, $min, $max) {
    return max($min, min($max, $value));
}

/**
 * Linear interpolation
 */
function egw_lerp($start, $end, $amount) {
    return $start + ($end - $start) * $amount;
}

/**
 * Map value from one range to another
 */
function egw_map_range($value, $in_min, $in_max, $out_min, $out_max) {
    return ($value - $in_min) * ($out_max - $out_min) / ($in_max - $in_min) + $out_min;
}
