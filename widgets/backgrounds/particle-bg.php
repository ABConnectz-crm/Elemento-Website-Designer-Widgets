<?php
namespace EGW_Widgets;

if (!defined('ABSPATH')) {
    exit;
}

class Particle_Background_Widget extends Widget_Base {

    public function get_name() {
        return 'egw-particle-background';
    }

    public function get_title() {
        return __('Particle Background', 'elementor-gsap-widgets');
    }

    public function get_icon() {
        return 'eicon-lightbox';
    }

    public function get_categories() {
        return ['egw-backgrounds'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'content',
            [
                'label' => __('Content', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => __('<h2>Particle Background</h2><p>Add your content here</p>', 'elementor-gsap-widgets'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_particle_settings',
            [
                'label' => __('Particle Settings', 'elementor-gsap-widgets'),
            ]
        );

        $this->add_control(
            'particle_count',
            [
                'label' => __('Particle Count', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 50,
                ],
            ]
        );

        $this->add_control(
            'particle_color',
            [
                'label' => __('Particle Color', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'particle_size',
            [
                'label' => __('Particle Size', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'size' => 3,
                ],
            ]
        );

        $this->add_control(
            'particle_speed',
            [
                'label' => __('Animation Speed', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 1,
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Background', 'elementor-gsap-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => __('Background Color', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#0a0a0a',
                'selectors' => [
                    '{{WRAPPER}} .egw-particle-background-widget' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'min_height',
            [
                'label' => __('Min Height', 'elementor-gsap-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 400,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .egw-particle-background-widget' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_id = 'egw-particle-' . $this->get_id();

        ?>
        <div class="egw-widget egw-particle-background-widget">
            <canvas id="<?php echo esc_attr($widget_id); ?>"
                    class="egw-particle-canvas"
                    data-particle-count="<?php echo esc_attr($settings['particle_count']['size']); ?>"
                    data-particle-color="<?php echo esc_attr($settings['particle_color']); ?>"
                    data-particle-size="<?php echo esc_attr($settings['particle_size']['size']); ?>"
                    data-particle-speed="<?php echo esc_attr($settings['particle_speed']['size']); ?>"></canvas>
            <div class="egw-content">
                <?php echo $settings['content']; ?>
            </div>
        </div>
        <script>
        (function() {
            const canvas = document.getElementById('<?php echo esc_js($widget_id); ?>');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            const container = canvas.parentElement;
            canvas.width = container.offsetWidth;
            canvas.height = container.offsetHeight;

            const particleCount = parseInt(canvas.dataset.particleCount) || 50;
            const particleColor = canvas.dataset.particleColor || '#ffffff';
            const particleSize = parseInt(canvas.dataset.particleSize) || 3;
            const particleSpeed = parseFloat(canvas.dataset.particleSpeed) || 1;

            const particles = [];

            class Particle {
                constructor() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.vx = (Math.random() - 0.5) * particleSpeed;
                    this.vy = (Math.random() - 0.5) * particleSpeed;
                    this.radius = particleSize;
                }

                update() {
                    this.x += this.vx;
                    this.y += this.vy;

                    if (this.x < 0 || this.x > canvas.width) this.vx *= -1;
                    if (this.y < 0 || this.y > canvas.height) this.vy *= -1;
                }

                draw() {
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                    ctx.fillStyle = particleColor;
                    ctx.fill();
                }
            }

            for (let i = 0; i < particleCount; i++) {
                particles.push(new Particle());
            }

            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                particles.forEach(particle => {
                    particle.update();
                    particle.draw();
                });

                requestAnimationFrame(animate);
            }

            animate();

            window.addEventListener('resize', function() {
                canvas.width = container.offsetWidth;
                canvas.height = container.offsetHeight;
            });
        })();
        </script>
        <?php
    }
}
