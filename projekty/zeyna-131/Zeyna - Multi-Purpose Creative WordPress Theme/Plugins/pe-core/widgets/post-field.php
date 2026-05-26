<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PePostField extends Widget_Base
{

    /**
     * Retrieve the widget name.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'pepostfield';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Post Field', 'pe-core');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-post-info pe-widget';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['pe-dynamic'];
    }


    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function _register_controls()
    {


        // Tab Title Control
        $this->start_controls_section(
            'section_tab_title',
            [
                'label' => __('Post Fields', 'pe-core'),
            ]
        );

        $this->add_control(
            'field_type',
            [
                'label' => esc_html__('Field Type', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'title',
                'options' => [
                    'title' => esc_html__('Title', 'pe-core'),
                    'category' => esc_html__('Category', 'pe-core'),
                    'author' => esc_html__('Author', 'pe-core'),
                    'date' => esc_html__('Date', 'pe-core'),
                    'excerpt' => esc_html__('Excerpt', 'pe-core'),
                    'tags' => esc_html__('Tags', 'pe-core'),
                    'content' => esc_html__('Content', 'pe-core'),
                    'comments' => esc_html__('Comments', 'pe-core'),
                    'read_time' => esc_html__('Read Time', 'pe-core'),
                ],
                'label_block' => true,
            ]
        );


        $this->add_control(
            'text_type',
            [
                'label' => esc_html__('Text Size', 'pe-core'),
                'description' => esc_html__('This option will not change HTML tag of the element, this option only for typographic scaling.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'text-p' => [
                        'title' => esc_html__('P', 'pe-core'),
                        'icon' => ' eicon-editor-paragraph',
                    ],
                    'text-h1' => [
                        'title' => esc_html__('H1', 'pe-core'),
                        'icon' => ' eicon-editor-h1',
                    ],
                    'text-h2' => [
                        'title' => esc_html__('H2', 'pe-core'),
                        'icon' => ' eicon-editor-h2',
                    ],
                    'text-h3' => [
                        'title' => esc_html__('H3', 'pe-core'),
                        'icon' => ' eicon-editor-h3',
                    ],
                    'text-h4' => [
                        'title' => esc_html__('H4', 'pe-core'),
                        'icon' => ' eicon-editor-h4',
                    ],
                    'text-h5' => [
                        'title' => esc_html__('H5', 'pe-core'),
                        'icon' => ' eicon-editor-h5',
                    ],
                    'text-h6' => [
                        'title' => esc_html__('H6', 'pe-core'),
                        'icon' => ' eicon-editor-h6',
                    ]

                ],
                'default' => 'text-p',
                'toggle' => true,
            ]
        );

        $this->add_control(
            'paragraph_size',
            [
                'label' => esc_html__('Paragraph Size', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Normal', 'pe-core'),
                    'p-small' => esc_html__('Small', 'pe-core'),
                    'p-large' => esc_html__('Large', 'pe-core'),

                ],
                'label_block' => true,
                'condition' => ['text_type' => 'text-p'],
            ]
        );

        $this->add_control(
            'heading_size',
            [
                'label' => esc_html__('Heading Size', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Normal', 'pe-core'),
                    'md-title' => esc_html__('Medium', 'pe-core'),
                    'big-title' => esc_html__('Large', 'pe-core'),

                ],
                'label_block' => true,
                'condition' => ['text_type' => 'text-h1'],
            ]
        );


        $this->add_control(
            'secondary_color',
            [
                'label' => esc_html__('Use Secondary Color', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'use--sec--color',
                'default' => '',
                'prefix_class' => '',

            ]
        );




        $this->add_control(
            'remove_breaks',
            [
                'label' => esc_html__('Remove Breaks on Mobile', 'pe-core'),
                'description' => esc_html__('On mobile screens "br" tags will be removed.', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'hide-br-mobile',
                'default' => '',

            ]
        );


        $this->add_control(
            'remove_margins',
            [
                'label' => esc_html__('Remove Margins', 'pe-core'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'pe-core'),
                'label_off' => esc_html__('No', 'pe-core'),
                'return_value' => 'no-margin',
                'default' => '',

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style',
            [

                'label' => esc_html__('Style', 'pe-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'label' => esc_html__('Typography', 'pe-core'),
                'selector' => '{{WRAPPER}} .text-wrapper p',
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'pe-core'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'pe-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'pe-core'),
                        'icon' => 'eicon-text-align-center'
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'pe-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justify', 'pe-core'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => is_rtl() ? 'right' : 'left',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .text-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        objectStyles($this, 'field_styles', 'Field', '.text-wrapper.pe--styled--object', false, false, false, false, true);

        $this->end_controls_section();

        pe_text_animation_settings($this);

        pe_color_options($this);
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $text = 'dummy';
        $type = $settings['field_type'];

        $this->add_render_attribute(
            'attributes',
            [
                'class' => [$settings['text_type'], $settings['paragraph_size'], $settings['remove_margins'], $settings['remove_breaks']],
            ]
        );

        global $wp_query;

        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'order' => 'ASC'
        );

        $loop = new \WP_Query($args);
        wp_reset_postdata();

        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {

            while ($loop->have_posts()):
                $loop->the_post();

                $id = get_the_ID();

            endwhile;
            wp_reset_query();

        } else {

            $id = $wp_query->post->ID;
        }

        if ($type === 'title') {

            $text = get_the_title($id);

        } else if ($type === 'read_time') {

            $post = get_post($id);
            $content = strip_tags($post->post_content);
            $word_count = str_word_count($content);
            $words_per_minute = 200;

            $minutes = floor($word_count / $words_per_minute);
            $seconds = floor($word_count % $words_per_minute / ($words_per_minute / 60));

            if ($minutes >= 1) {
                $text = $minutes . ' min read';
            } else {
                $text = $seconds . ' sec read';
            }

        } else if ($type === 'excerpt') {

            $text = get_the_excerpt($id);

        } else if ($type === 'category') {

            $text = get_the_category_list(esc_html__(' ', 'pe-theme'));


        } else if ($type === 'author') {

            $text = sprintf(
                /* translators: %s: post author. */
                esc_html_x('%s', 'post author', 'pe-theme'),
                '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
            );


        } else if ($type === 'date') {

            $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
            if (get_the_time('U') !== get_the_modified_time('U')) {
                $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
            }

            $time_string = sprintf(
                $time_string,
                esc_attr(get_the_date(DATE_W3C)),
                esc_html(get_the_date()),
                esc_attr(get_the_modified_date(DATE_W3C)),
                esc_html(get_the_modified_date())
            );

            $text = sprintf(
                /* translators: %s: post date. */
                esc_html_x('%s', 'post date', 'pe-theme'),
                '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
            );

        } else if ($type === 'tags') {

            $text = get_the_tag_list('', esc_html_x('  ', '', 'pe-theme'));


        } else if ($type === 'content') {

            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {

                $text = the_content($id);

            } else {

                $text = the_content($id);

            }

        }
        ?>
        <?php if ($type === 'comments') {

            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) { ?>

                <div id="comments" class="comments-area">

                    <h5 class="comments-title">
                        One thought on “<span><?php echo get_the_title($id) ?></span>” </h5>
                    <!-- .comments-title -->

                    <ol class="comment-list">
                        <li>
                            <div class="comment">
                                <div class="comment-meta">
                                    <div class="image"> <img alt=""
                                            src="https://secure.gravatar.com/avatar/b8918f228c6c43181d3c638a952582212c769983e133a49ace3911d425b8128b?s=75&amp;d=mm&amp;r=g"
                                            srcset="https://secure.gravatar.com/avatar/b8918f228c6c43181d3c638a952582212c769983e133a49ace3911d425b8128b?s=150&amp;d=mm&amp;r=g 2x"
                                            class="avatar avatar-75 photo" height="75" width="75" decoding="async"> </div>

                                    <div class="comment-usr">

                                        <h6 class="name">
                                            <a href="http://localhost/zeyna2" class="url"
                                                rel="ugc"><?php echo get_the_author_meta('display_name') ?></a>
                                        </h6>
                                        <span class="comment_date"><?php echo __('May 26, 2025', 'pe-core') ?></span>

                                    </div>

                                </div>

                                <div class="text_holder" id="comment-2">
                                    <p><?php echo __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse in bibendum nisi.
                                        Pellentesque quis luctus turpis. Nam posuere arcu et aliquet blandit. Sed imperdiet elit nec
                                        neque rutrum luctus in eu odio. Nam mauris sapien, egestas a ex sed, fringilla efficitur massa.
                                        Morbi hendrerit, orci sit amet tristique semper, dui est volutpat purus, quis fringilla erat
                                        tortor eu purus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per
                                        inceptos himenaeos.', 'pe-core') ?></p>
                                </div>

                                <a rel="nofollow" class="comment-reply-link"><?php echo __('Reply', 'pe-core') ?></a>
                            </div>

                        </li><!-- #comment-## -->
                    </ol><!-- .comment-list -->

                    <div id="respond" class="comment-respond">
                        <h3 id="reply-title" class="comment-reply-title"><?php echo __('Leave a reply', 'pe-core') ?> <small><a
                                    rel="nofollow" id="cancel-comment-reply-link"
                                    style="display:none;"><?php echo __('Cancel reply', 'pe-core') ?> </a></small></h3>
                        <form id="commentform" class="comment-form" novalidate="">
                            <p class="logged-in-as"><?php echo __('Logged in as zeyna.', 'pe-core') ?> <a
                                    href="http://localhost/zeyna2/wp-admin/profile.php"><?php echo __('Edit your
                                    profile', 'pe-core') ?></a>. <a><?php echo __('Log out?', 'pe-core') ?></a> <span
                                    class="required-field-message"><?php echo __('Required fields are marked', 'pe-core') ?> <span
                                        class="required">*</span></span></p>
                            <p class="comment-form-comment"><label for="comment"><?php echo __('Comment', 'pe-core') ?> <span
                                        class="required">*</span></label>
                                <textarea placeholder="Write your comment here" id="comment" name="comment" cols="45" rows="8"
                                    maxlength="65525" required=""></textarea>
                            </p>
                            <p class="form-submit"><input name="submit" type="submit" id="submit" class="submit" value="Post Comment">
                            </p>
                            ]
                        </form>
                    </div><!-- #respond -->

                </div>

            <?php } else {
                if (comments_open() || get_comments_number()):
                    comments_template();
                endif;

            }


        } else { ?>

            <div class="text-wrapper pe--styled--object">

                <p <?php echo $this->get_render_attribute_string('attributes') ?>             <?php echo pe_text_animation($this) ?>>
                    <?php echo $text; ?>
                </p>

            </div>

        <?php } ?>

        <?php
    }

}
