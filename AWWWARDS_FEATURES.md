# Awwwards-Style Advanced GSAP Widgets

## 🎯 Overview

This plugin now includes **Awwwards-caliber animation widgets** using **ONLY FREE GSAP plugins**. All widgets are fully customizable with comprehensive Elementor controls and responsive design built-in.

## ✅ Key Philosophy

- **FREE GSAP ONLY**: Uses GSAP Core, ScrollTrigger, and Flip (all free)
- **NO PAID PLUGINS**: No SplitText (paid) - we built `EGWTextSplitter` as a free alternative
- **FULLY CUSTOMIZABLE**: Every parameter is exposed in Elementor's visual editor
- **RESPONSIVE**: All animations adapt automatically to different screen sizes
- **PERFORMANCE**: Optimized with GPU acceleration, will-change properties, and proper cleanup

---

## 🚀 New Advanced Widgets

### 1. **Kinetic 3D Cylinder Text** (`egw-kinetic-3d-text`)

Creates text that wraps around a virtual 3D cylinder with scroll-driven or auto rotation.

**Features:**
- Text characters positioned on a 3D cylinder
- Customizable radius and perspective
- Multiple rotation modes: scroll-driven, auto-rotate, hover-rotate
- Depth-based fade and blur effects
- Full rotation control (speed, direction, initial angle)

**Use Cases:**
- Hero section headings
- Product showcase titles
- Portfolio headers

**Location:** `widgets/text-animations/kinetic-3d-text.php`
**Handler:** `assets/js/widgets/kinetic-3d-handler.js`

---

### 2. **Skew & Stagger Text Reveal** (`egw-skew-reveal-text`)

Premium text reveal with physics-based skew and 3D rotation animation.

**Features:**
- Split text by lines, words, or characters
- 3D rotation reveal with skew distortion
- Customizable reveal direction (up, down, left, right)
- Stagger control with various easing options
- Optional clip-path masking for clean reveals

**Use Cases:**
- Section headings with impact
- Call-to-action text
- Animated testimonials

**Location:** `widgets/text-animations/skew-reveal-text.php`
**Handler:** `assets/js/widgets/skew-reveal-handler.js`

---

### 3. **Advanced Clip-Path Reveal** (`egw-clippath-reveal`)

Sophisticated reveal animations using CSS clip-path transitions.

**Features:**
- Multiple reveal shapes:
  - Circle expand
  - Diagonal wipe
  - Center box expand
  - Horizontal/vertical curtains
  - Diamond and hexagon expansions
- Customizable origin points (center, corners, mouse-follow)
- Works with images, videos, and text
- Optional content scaling during reveal

**Use Cases:**
- Image galleries
- Video reveals
- Section transitions

**Location:** `widgets/ui-elements/advanced-clippath-reveal.php`
**Handler:** `assets/js/widgets/clippath-reveal-handler.js`

---

### 4. **Scrollytelling Pin Container** (`egw-scrollytelling-pin`)

Creates Awwwards-style pinned scroll sections with stacking card effects.

**Features:**
- Multiple panel system
- 4 effect types:
  - Stacking cards (scale + offset)
  - Slide over
  - Fade & scale
  - Rotate out
- Customizable pin duration
- Per-panel backgrounds and content
- Smooth ScrollTrigger integration

**Use Cases:**
- Product feature showcases
- Case study presentations
- Storytelling sections

**Location:** `widgets/ui-elements/scrollytelling-pin.php`
**Handler:** `assets/js/widgets/scrollytelling-pin-handler.js`

---

### 5. **Horizontal Scroll Section** (`egw-horizontal-scroll`)

Vertical scroll drives horizontal movement across sections.

**Features:**
- Vertical-to-horizontal scroll translation
- Multiple sections with independent content
- Customizable scroll speed and smoothness
- Optional snap points
- Per-section parallax effects
- Fully responsive

**Use Cases:**
- Timeline presentations
- Portfolio showcases
- Product lineups

**Location:** `widgets/ui-elements/horizontal-scroll.php`
**Handler:** `assets/js/widgets/horizontal-scroll-handler.js`

---

## 🛠 Technical Architecture

### Free Text Splitter

**`EGWTextSplitter` Class** (`assets/js/text-splitter.js`)

A comprehensive, free alternative to GSAP's paid SplitText plugin.

**Features:**
- Split by lines, words, and characters
- Preserves semantic HTML structure
- Responsive reflow capability
- Accessibility attributes (aria-label, aria-hidden)
- No external dependencies

**Usage:**
```javascript
const splitter = new EGWTextSplitter(element, {
    type: 'lines,words,chars',
    linesClass: 'egw-line',
    wordsClass: 'egw-word',
    charsClass: 'egw-char'
});

// Access split elements
console.log(splitter.lines);
console.log(splitter.words);
console.log(splitter.chars);

// Revert when done
splitter.revert();

// Responsive reflow
window.addEventListener('resize', () => {
    splitter.reflow();
});
```

---

### Responsive Handling Pattern

All widgets follow a consistent responsive pattern:

1. **Setup Phase**: Initialize animation with current viewport
2. **Resize Handler**: Debounced (250-300ms) to prevent thrashing
3. **Cleanup Phase**: Revert splits, kill ScrollTriggers, clean context
4. **Reinitialize**: Recreate animations with new dimensions

**Example Pattern:**
```javascript
setupResizeHandler(element, settings) {
    let resizeTimer;
    const handleResize = () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            // 1. Kill GSAP context
            if (element._gsapContext) {
                element._gsapContext.kill();
            }

            // 2. Revert text split
            if (element._splitter) {
                element._splitter.revert();
            }

            // 3. Reinitialize
            this.initAnimation(element, settings);
        }, 250);
    };

    window.addEventListener('resize', handleResize);
    element._resizeHandler = handleResize;
}
```

---

### Performance Optimizations

#### 1. **GPU Acceleration**
```css
.egw-kinetic-3d-text,
.egw-skew-reveal-text,
.egw-pin-panel {
    transform: translateZ(0);
    -webkit-transform: translateZ(0);
}
```

#### 2. **Will-Change Properties**
```css
.egw-3d-char {
    will-change: transform, opacity, filter;
}
```

#### 3. **Layout Containment**
```css
.egw-3d-char,
.egw-line,
.egw-word {
    contain: layout style;
}
```

#### 4. **GSAP Context for Cleanup**
```javascript
const ctx = gsap.context(() => {
    // All animations here
}, element);

// Later cleanup
ctx.kill();
```

#### 5. **ScrollTrigger Optimization**
```javascript
ScrollTrigger.config({
    limitCallbacks: true,
    ignoreMobileResize: true,
});
```

---

## 📱 Responsive Design

All widgets include responsive breakpoints:

- **Desktop** (> 1024px): Full effects
- **Tablet** (768px - 1024px): Reduced perspective, adjusted font sizes
- **Mobile** (< 768px): Simplified 3D effects for performance
- **Small Mobile** (< 480px): Further optimizations

**Example:**
```css
@media (max-width: 768px) {
    .egw-kinetic-3d-container {
        perspective: 800px; /* Reduced from 1000px */
    }
}
```

---

## ♿ Accessibility Features

### 1. **Semantic HTML Preservation**
Text splitting maintains screen reader compatibility:

```javascript
// Parent gets full text
element.setAttribute('aria-label', originalText);

// Split fragments are hidden from screen readers
chars.forEach(char => char.setAttribute('aria-hidden', 'true'));
```

### 2. **Reduced Motion Support**
Respects user preferences:

```css
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        transition-duration: 0.01ms !important;
    }
}
```

### 3. **Keyboard Navigation**
```css
.egw-widget:focus-visible {
    outline: 2px solid currentColor;
    outline-offset: 4px;
}
```

---

## 🎨 Customization Guide

Every widget exposes full control through Elementor:

### Kinetic 3D Text Controls
- **Content**: Text, HTML tag
- **3D Settings**: Cylinder radius, perspective, rotation axis, character spacing, depth effects
- **Rotation**: Speed, direction, initial angle
- **Typography**: Full typography control, text color, stroke
- **Animation**: Duration, delay, easing
- **ScrollTrigger**: Start/end positions, scrub, pin, markers

### Skew Reveal Text Controls
- **Content**: Text, HTML tag, split type (lines/words/chars)
- **Reveal Animation**: Direction, skew amount, 3D rotation, distance, clip masking
- **Stagger**: Amount, from position, easing curve
- **Typography**: Full control with perspective adjustment
- **ScrollTrigger**: Complete configuration

### Clip-Path Reveal Controls
- **Content**: Image, video, or text
- **Clip-Path**: 7 reveal shapes, origin point (including mouse-follow)
- **Animation**: Duration, easing, optional content scaling
- **Style**: Height, background color
- **ScrollTrigger**: Full configuration

### Scrollytelling Pin Controls
- **Panels**: Repeater field for unlimited panels
  - Title, content, background color, background image per panel
- **Pin Settings**: Effect type, pin spacer, scale amount, stack offset
- **Panel Style**: Height, border radius, box shadow

### Horizontal Scroll Controls
- **Sections**: Repeater field for unlimited sections
  - Title, content, background per section
- **Scroll Settings**: Speed, smooth scrub, snap points
- **Section Style**: Width, height, gap between sections

---

## 📦 File Structure

```
Elemento-Website-Designer-Widgets/
├── assets/
│   ├── css/
│   │   └── base-animations.css (Enhanced with new widget styles)
│   └── js/
│       ├── text-splitter.js (FREE SplitText alternative)
│       └── widgets/
│           ├── kinetic-3d-handler.js
│           ├── skew-reveal-handler.js
│           ├── clippath-reveal-handler.js
│           ├── scrollytelling-pin-handler.js
│           └── horizontal-scroll-handler.js
├── widgets/
│   ├── text-animations/
│   │   ├── kinetic-3d-text.php
│   │   └── skew-reveal-text.php
│   └── ui-elements/
│       ├── advanced-clippath-reveal.php
│       ├── scrollytelling-pin.php
│       └── horizontal-scroll.php
└── elementor-gsap-widgets.php (Updated with new registrations)
```

---

## 🎓 Usage Examples

### Example 1: Kinetic 3D Cylinder Heading

```php
// In Elementor, add "Kinetic 3D Cylinder Text" widget
// Set text: "CREATIVE STUDIO"
// Animation: scroll-rotate
// Cylinder radius: 300px
// Character spacing: 10deg
// Enable depth fade: Yes
```

Result: Text wraps on a 3D cylinder, rotating as you scroll, with characters fading based on depth.

---

### Example 2: Skew Reveal Section Heading

```php
// Add "Skew Reveal Text" widget
// Text: "Premium Services"
// Split by: Lines
// Reveal direction: From Bottom
// Skew amount: 7deg
// 3D rotation: -45deg
// Stagger: 0.1s
// ScrollTrigger: Start at "top 80%"
```

Result: Each line slides up with a skew effect, creating a dynamic cascading reveal.

---

### Example 3: Scrollytelling Product Showcase

```php
// Add "Scrollytelling Pin Container" widget
// Add 4 panels with different product features
// Effect type: Stacking cards
// Pin spacer: 100vh per panel
// Scale amount: 0.9
```

Result: Each product feature stays pinned while you scroll, then scales down as the next one slides over.

---

### Example 4: Portfolio Timeline

```php
// Add "Horizontal Scroll Section" widget
// Add 5 project sections
// Scroll speed: 1.5
// Smooth scrub: 2
// Enable snap: Yes
```

Result: Vertical scroll drives horizontal movement through portfolio items with smooth inertia.

---

## 🐛 Debugging

Enable debug mode in the plugin:

```javascript
// In egw-gsap-config.js
wp_localize_script('egw-gsap-config', 'egwSettings', [
    'debugMode' => true,
]);
```

This will log:
- GSAP version
- Plugin availability
- Widget initialization
- ScrollTrigger events

**Add markers to ScrollTriggers:**

```php
// In Elementor widget settings
ScrollTrigger Settings > Show Markers (Debug): Yes
```

---

## 📝 Best Practices

### 1. **Performance**
- Use `scrub` for scroll-driven animations (prevents janky playback)
- Limit simultaneous 3D animations on mobile
- Use `will-change` sparingly (only on actively animating elements)

### 2. **Responsive**
- Test animations on mobile devices
- Reduce perspective values on smaller screens
- Simplify stagger amounts on mobile

### 3. **Accessibility**
- Always provide alternative content for screen readers
- Test with keyboard navigation
- Respect `prefers-reduced-motion`

### 4. **Content**
- Keep text concise for better animation clarity
- Use high-contrast colors for depth-faded text
- Test on different background colors

---

## 🔧 Extending

### Adding a New Widget

1. **Create PHP Widget Class** (`widgets/[category]/your-widget.php`)
```php
class Your_Widget extends Widget_Base {
    public function get_script_depends() {
        return ['gsap', 'gsap-scrolltrigger', 'egw-your-handler'];
    }
}
```

2. **Create JavaScript Handler** (`assets/js/widgets/your-handler.js`)
```javascript
class YourHandler extends elementorModules.frontend.handlers.Base {
    run() {
        // Animation logic
    }
}

$(window).on('elementor/frontend/init', () => {
    elementorFrontend.hooks.addAction(
        'frontend/element_ready/egw-your-widget.default',
        ($element) => {
            elementorFrontend.elementsHandler.addHandler(YourHandler, {
                $element,
            });
        }
    );
});
```

3. **Register in Main Plugin** (`elementor-gsap-widgets.php`)
```php
require_once EGW_WIDGETS_PATH . '[category]/your-widget.php';
$widgets_manager->register(new \EGW_Widgets\Your_Widget());
```

4. **Register Handler Script**
```php
wp_register_script(
    'egw-your-handler',
    EGW_ASSETS_URL . 'js/widgets/your-handler.js',
    ['jquery', 'gsap'],
    EGW_VERSION,
    true
);
```

---

## 📚 Resources

- **GSAP Documentation**: https://greensock.com/docs/
- **ScrollTrigger Docs**: https://greensock.com/docs/v3/Plugins/ScrollTrigger
- **Elementor Widget Development**: https://developers.elementor.com/
- **Awwwards Inspiration**: https://www.awwwards.com/

---

## 🏆 Awwwards Trends Implemented

Based on the 2024-2025 analysis:

✅ **Dimensional Kinetic Typography** - Kinetic 3D Text widget
✅ **Scrollytelling via Pinned Viewports** - Scrollytelling Pin Container
✅ **Mask-Based Reveals** - Advanced Clip-Path Reveal
✅ **Horizontal Scroll** - Horizontal Scroll Section
✅ **Physics-Based Motion** - Skew Reveal with proper easing
✅ **3D Transforms** - All text widgets use preserve-3d
✅ **Responsive Integrity** - All widgets adapt to viewport
✅ **Accessibility** - Screen reader support, reduced motion
✅ **Performance** - GPU acceleration, proper cleanup
✅ **Free Tools Only** - No paid GSAP plugins required

---

## 🎉 Conclusion

This plugin now delivers **professional-grade, Awwwards-style animations** using only free GSAP plugins. Every component is fully customizable through Elementor's interface, making advanced animations accessible to designers without coding.

**Key Achievements:**
- ✅ 100% Free GSAP plugins only
- ✅ Fully customizable via Elementor
- ✅ Responsive and accessible
- ✅ Performance optimized
- ✅ Production-ready code

Happy animating! 🚀
