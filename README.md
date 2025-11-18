# Elementor GSAP Widgets Factory

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![WordPress](https://img.shields.io/badge/WordPress-5.8+-green.svg)
![Elementor](https://img.shields.io/badge/Elementor-3.0+-orange.svg)
![PHP](https://img.shields.io/badge/PHP-7.4+-purple.svg)

A comprehensive Elementor widget library featuring modern, scroll-triggered animations powered by GSAP (GreenSock Animation Platform). This factory provides a collection of highly customizable, performance-optimized widgets with sophisticated animation capabilities.

## 🎯 Features

- **Advanced Text Animations** - Glassmorphism, staggered reveals, split text effects, gradient animations, typewriter effects, and morphing text
- **Image Animations** - Parallax scrolling, masked images, reveal animations, and zoom/pan effects
- **Animated Backgrounds** - Particle systems, gradient animations, and wave effects
- **Modern UI Elements** - Icon boxes, flip boxes, marquee, timeline, and comparison sliders
- **GSAP ScrollTrigger Integration** - Full control over scroll-based animations
- **Performance Optimized** - Lazy loading, GPU acceleration, and proper cleanup
- **Fully Responsive** - Works beautifully on all devices
- **Accessibility Ready** - Respects prefers-reduced-motion settings

## 📦 Installation

1. Upload the plugin files to the `/wp-content/plugins/elementor-gsap-widgets` directory
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Make sure Elementor is installed and activated
4. Start using the widgets in Elementor editor

## 🎨 Widget Categories

### Text Animation Widgets

#### 1. Glassmorphism Text
Beautiful frosted glass effect with customizable blur and opacity settings.

**Features:**
- Light and dark themes
- Adjustable blur amount
- Customizable background opacity
- Border radius controls
- Typography options

#### 2. Staggered Text
Sequential word-by-word or character-by-character animations.

**Features:**
- Split by words or characters
- Word integrity preservation
- Customizable stagger amount
- Multiple animation types
- ScrollTrigger support

#### 3. Split Text
Advanced text splitting animations using GSAP SplitText plugin.

**Features:**
- Requires GSAP SplitText plugin
- Professional text animations
- Full animation control
- Stagger effects

#### 4. Gradient Text
Animated gradient overlay on text.

**Features:**
- Customizable gradient colors
- Adjustable gradient angle
- Optional gradient animation
- Full typography control

#### 5. Typewriter Text
Progressive character display effect.

**Features:**
- Adjustable typing speed
- Optional blinking cursor
- ScrollTrigger integration
- Monospace font support

#### 6. Morphing Text
Smooth transitions between multiple text states.

**Features:**
- Multiple text variations
- Customizable morph duration
- Adjustable delay between morphs
- Fade and slide effects

### Image Animation Widgets

#### 1. Parallax Image
Multi-layer depth effect on scroll.

**Features:**
- Adjustable parallax speed
- Vertical or horizontal direction
- Customizable height
- Border radius controls

#### 2. Masked Image
Geometric and custom masks with animations.

**Features:**
- Circle, polygon, and blob masks
- Animated mask options
- Responsive width controls
- ScrollTrigger support

#### 3. Reveal Image
Clip-path based reveal animations.

**Features:**
- 4 reveal directions (left, right, top, bottom)
- Customizable animation duration
- ScrollTrigger integration
- Border radius controls

#### 4. Zoom & Pan Image
Ken Burns-style image animations.

**Features:**
- Adjustable zoom scale
- Hover or scroll trigger
- Smooth transitions
- Border radius options

### Background Widgets

#### 1. Particle Background
Canvas-based particle animation system.

**Features:**
- Adjustable particle count
- Customizable particle color and size
- Animation speed control
- Background color settings

#### 2. Gradient Background
Animated gradient backgrounds.

**Features:**
- 4-color gradient support
- Customizable animation duration
- Adjustable height
- Content overlay support

#### 3. Wave Background
Fluid wave animations.

**Features:**
- Customizable wave color
- Adjustable opacity
- Wave speed control
- SVG-based animation

### UI Element Widgets

#### 1. Icon Box
Animated icon boxes with hover effects.

**Features:**
- FontAwesome icon support
- Customizable icon size and color
- Hover animations
- ScrollTrigger integration

#### 2. Flip Box
3D flip box with front and back content.

**Features:**
- Horizontal and vertical flip
- Hover or click trigger
- Customizable height
- Smooth GSAP animations

#### 3. Marquee
Infinite scrolling text marquee.

**Features:**
- Repeater field for items
- Adjustable speed
- Pause on hover option
- Full typography control

#### 4. Timeline
Vertical timeline with scroll animations.

**Features:**
- Repeater for timeline items
- Customizable line and marker colors
- Batch animations
- Responsive layout

#### 5. Comparison Slider
Before/after image comparison slider.

**Features:**
- Draggable divider
- Click to position
- Touch support
- Initial position control

## ⚙️ GSAP Libraries Used

- **GSAP Core** (v3.12.5) - Main animation engine
- **ScrollTrigger** - Scroll-based animations
- **SplitText** - Advanced text splitting (requires license)
- **Flip** - Flip box animations
- **ScrollSmoother** - Enhanced scroll smoothness (optional)

## 🎛️ Animation Controls

All widgets include common animation controls:

### Basic Animation Settings
- Animation Type (fade-in, slide-up, slide-down, scale-up, etc.)
- Duration (0.1-5 seconds)
- Delay (0-5 seconds)
- Easing (30+ easing functions)

### ScrollTrigger Settings
- Enable/Disable ScrollTrigger
- Trigger Start Position
- Trigger End Position
- Scrub (link animation to scroll)
- Pin Element
- Toggle Actions
- Debug Markers

### Stagger Settings (Text Widgets)
- Stagger Amount
- Stagger From (start, center, end, edges, random)
- Preserve Word Integrity

## 🚀 Performance Optimization

### Built-in Optimizations
- **Lazy Loading** - Animations initialize only when in viewport
- **RAF (RequestAnimationFrame)** - Smooth 60fps animations
- **GPU Acceleration** - Uses transform and opacity properties
- **Proper Cleanup** - Destroys ScrollTrigger instances on element removal
- **Debounced Listeners** - Optimized resize and scroll event handlers
- **Mobile Optimization** - Reduced motion options for mobile devices

### Best Practices
1. Use fewer particles for particle backgrounds (30-50 recommended)
2. Limit the number of staggered text elements on a single page
3. Use `will-change` sparingly
4. Enable `scrub` for smoother scroll-linked animations
5. Test on actual devices, not just browser DevTools

## 🎨 Customization

### Adding Custom Animations

You can extend the animation types by modifying `assets/js/animation-utilities.js`:

```javascript
getAnimationValues: function(type) {
    const animations = {
        'custom-animation': {
            from: { opacity: 0, scale: 0.8, rotation: 45 },
            to: { opacity: 1, scale: 1, rotation: 0 }
        },
        // ... other animations
    };
    return animations[type] || animations['fade-in'];
}
```

### Creating Custom Widgets

Extend the base widget class:

```php
namespace EGW_Widgets;

class My_Custom_Widget extends Widget_Base {
    public function get_name() {
        return 'egw-my-custom-widget';
    }

    public function get_title() {
        return __('My Custom Widget', 'elementor-gsap-widgets');
    }

    // ... implementation
}
```

## 🔧 Troubleshooting

### Common Issues

**Animations not working:**
- Check if GSAP is loaded (view browser console)
- Verify Elementor is up to date (3.0.0+)
- Clear browser cache and Elementor cache

**ScrollTrigger not firing:**
- Check trigger start/end positions
- Enable debug markers to visualize triggers
- Ensure the trigger element is in the viewport

**Text not splitting correctly:**
- For SplitText plugin, verify it's loaded
- Check console for JavaScript errors
- Ensure text content is not empty

**Performance issues:**
- Reduce particle count in particle backgrounds
- Limit the number of animated elements per page
- Use `scrub` with higher smoothness value
- Enable browser hardware acceleration

## 📱 Browser Compatibility

- Chrome/Edge (Chromium) - Full support
- Firefox - Full support
- Safari (including iOS Safari) - Full support
- Opera - Full support
- IE11 - Not supported (GSAP 3 doesn't support IE11)

## ♿ Accessibility

The plugin respects user preferences:

- **prefers-reduced-motion** - Automatically disables animations
- **Keyboard Navigation** - All interactive elements are keyboard accessible
- **Screen Readers** - Proper ARIA labels where needed
- **Focus Styles** - Clear focus indicators for accessibility

## 📝 Changelog

### Version 1.0.0 (2024-11-18)
- Initial release
- 6 text animation widgets
- 4 image animation widgets
- 3 background widgets
- 5 UI element widgets
- Full GSAP ScrollTrigger integration
- Comprehensive documentation

## 🤝 Contributing

Contributions are welcome! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the GPL v3 or later - see the LICENSE file for details.

## 🙏 Credits

- **GSAP** - [GreenSock Animation Platform](https://greensock.com/gsap/)
- **Elementor** - [Elementor Page Builder](https://elementor.com/)
- **ABConnectz CRM** - Plugin development

## 📞 Support

For support, please:
1. Check the documentation above
2. Review the troubleshooting section
3. Open an issue on GitHub
4. Contact: support@abconnectz.com

## 🔗 Links

- [Documentation](https://github.com/ABConnectz-crm/Elemento-Website-Designer-Widgets)
- [GSAP Documentation](https://greensock.com/docs/)
- [Elementor Developer Docs](https://developers.elementor.com/)

---

Made with ❤️ by ABConnectz CRM
