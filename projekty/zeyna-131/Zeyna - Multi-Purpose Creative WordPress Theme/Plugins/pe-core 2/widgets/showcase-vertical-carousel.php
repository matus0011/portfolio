<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class PeShowcaseVerticalCarousel extends Widget_Base
{
    public function get_name()
    {
        return 'peshowcaseverticalcarousel';
    }

    public function get_title()
    {
        return __('Pe Showcase Vertical Carousel', 'pe-core');
    }

    public function get_icon()
    {
        return 'eicon-posts-carousel pe-widget';
    }

    public function get_categories()
    {
        return ['pe-showcase'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_project_title',
            [
                'label' => __('Showcase', 'pe-core'),
            ]
        );

        zeyna_project_query_selection($this, false, false); 

        $this->add_control(
            'speed',
            [
                'label' => esc_html__('Speed', 'pe-core'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1000,
                'max' => 20000,
                'step' => 100
            ]
        );

        $this->add_control(
            'pinned--elements',
            [
                'label' => esc_html__('Pinned Elements Class', 'pe-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'ai' => false,
                'placeholder' => 'Eg. ".outer-widgets"',
                'description' => esc_html__('Elements which has the class you entered will be pinned during the showcase scroll. You can add elements classes via "Advances -> CSS Classes" on the widget options.', 'pe-core'),
            ]
        );

        $this->add_control(
            'category',
            [
                'label' => esc_html__('Category', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes'
            ]
        );


        $this->end_controls_section();


        zeyna_project_settings($this, false);



        zeyna_project_styles($this, false);






        $this->start_controls_section(
            'style',
            [
                'label' => esc_html__('Image', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_responsive_control(
            'project_height',
            [
                'label' => esc_html__('Image Height', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                        'step' => 5
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase--vertical--carousel .showcase--project .project--image' => 'height: {{SIZE}}{{UNIT}}'
                ]
            ]
        );


        
        $this->add_responsive_control(
            'gap',
            [
                'label' => esc_html__('Project Gap', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 250,
                        'step' => 1
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase--vertical--carousel .showcase--vertical--carousel--wrapper' => 'gap: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $this->add_responsive_control(
            'meta_space',
            [
                'label' => esc_html__('Meta - Image Space', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 250,
                        'step' => 1
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase--vertical--carousel .showcase--vertical--carousel--wrapper .showcase--project .project--image' => 'margin-bottom: {{SIZE}}{{UNIT}}'
                ]
            ]
                );

        

        $this->end_controls_section();


        pe_color_options($this);
    }

    protected function render()
    {

        $settings = $this->get_settings_for_display();
        $id = $this->get_id();

        

        ob_start();
        \Elementor\Icons_Manager::render_icon($settings['head_icon'], ['aria-hidden' => 'true']);
        $icon = ob_get_clean();
        $classes = [];

        if ($settings['speed']) {
            $speed = $settings['speed'];
        } else {
            $speed = 5000;
        }


        ?>
        <div class="showcase--vertical--carousel anim-multiple" data-speed="<?php echo $speed; ?>" data-pin-target="<?php echo $settings['pinned--elements']; ?>">
            <div class="showcase--vertical--carousel--wrapper">
            <?php
                $loop = new \WP_Query(zeyna_project_query_args(($this)));
                while ($loop->have_posts()):
                    $loop->the_post();
                    $classes = 'showcase--project';
                    zeyna_project_render($this, $classes, false);
                endwhile;
                wp_reset_query();
                ?>
                <?php ?>
            </div>
        </div>
        <?php
    }
}
