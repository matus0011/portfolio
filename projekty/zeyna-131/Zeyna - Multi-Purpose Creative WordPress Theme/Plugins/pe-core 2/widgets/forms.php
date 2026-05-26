<?php
namespace PeElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
  exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class PeForms extends Widget_Base
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
    return 'peforms';
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
    return __('Forms', 'pe-core');
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
    return 'eicon-form-horizontal pe-widget';
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
        'label' => __('Form', 'pe-core'),
      ]
    );

    $this->add_control(
      'form_type',
      [
        'label' => esc_html__('Form Type ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'custom_form',
        'options' => [
          'custom_form' => esc_html__('Custom Form', 'pe-core'),
          'contact_form_7' => esc_html__('Contact Form 7', 'pe-core'),
        ],
      ]
    );

    $repeater = new \Elementor\Repeater();

    $repeater->start_controls_tabs(
      'field_tabs',

    );

    $repeater->start_controls_tab(
      'field_tab',
      [
        'label' => esc_html__('Field', 'pe-core'),
      ]
    );


    $repeater->add_control(
      'field_type',
      [
        'label' => __('Field Type', 'pe-core'),
        'type' => Controls_Manager::SELECT,
        'options' => [
          'text' => 'Text',
          'textarea' => 'Textarea',
          'url' => 'URL',
          'email' => 'Email',
          'number' => 'Number',
          'tel' => 'Tel',
          'date' => 'Date',
          'file' => 'File',
          'radio' => 'Radio Buttons',
          'select' => 'Select',
          'checkbox' => 'Checkbox',
          'label' => 'Label',
        ],
        'default' => 'text',
      ]
    );

    $repeater->add_control(
      'field_label',
      [
        'label' => __('Label', 'pe-core'),
        'type' => Controls_Manager::TEXT,
        'ai' => false,
      ]
    );

    $repeater->add_control(
      'field_placeholder',
      [
        'label' => __('Placeholder', 'pe-core'),
        'type' => Controls_Manager::TEXT,
        'ai' => false,
      ]
    );

    $repeater->add_control(
      'field_default_value',
      [
        'label' => __('Default Value', 'pe-core'),
        'type' => Controls_Manager::TEXT,
        'ai' => false,
        'condition' => ['field_type!' => ['date', 'file', 'radio', 'select', 'checkbox']],
      ]
    );

    $repeater->add_control(
      'field_name',
      [
        'label' => __('Name', 'pe-core'),
        'type' => Controls_Manager::TEXT,
        'default' => 'field_name',
        'ai' => false,
      ]
    );


    $repeater->add_responsive_control(
      'textarea_rows',
      [
        'label' => esc_html__('Rows', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => [''],
        'range' => [
          '' => [
            'min' => 1,
            'max' => 100,
            'step' => 1,
          ],
        ],
        'default' => [
          'unit' => '',
          'size' => 5,
        ],
        'condition' => ['field_type' => 'textarea'],
      ]
    );

    $repeater->add_control(
      'field_options',
      [
        'label' => __('Options', 'pe-core'),
        'description' => __('Wrap each option to a line.', 'pe-core'),
        'type' => Controls_Manager::TEXTAREA,
        'ai' => false,
        'condition' => ['field_type' => ['radio', 'select', 'checkbox']],
      ]
    );

    $repeater->add_control(
      'file_types',
      [
        'label' => __('File Types', 'pe-core'),
        'label' => __('Allowed File Types (comma separated)', 'my-plugin'),
        'type' => Controls_Manager::TEXT,
        'default' => '.jpg, .jpeg, .png, .ai, .psd, .pdf, .eps',
        'condition' => ['field_type' => 'file'],
        'ai' => false,
        'label_block' => true,
      ]
    );
    $repeater->add_control(
      'max_file_size',
      [
        'label' => __('Max File Size (MB)', 'pe-core'),
        'type' => Controls_Manager::NUMBER,
        'default' => 5,
        'condition' => ['field_type' => 'file'],
      ]
    );

    $repeater->add_control(
      'allow_multiple_files',
      [
        'label' => esc_html__('Multiple Files', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'return_value' => 'multiple',
        'default' => '',
        'condition' => ['field_type' => 'file'],

      ]
    );

    $repeater->add_control(
      'is_required',
      [
        'label' => esc_html__('Required?', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'return_value' => 'required',
        'default' => '',
      ]
    );

    $repeater->end_controls_tab();

    $repeater->start_controls_tab(
      'field_styles_tab',
      [
        'label' => esc_html__('Style', 'pe-core'),
      ]
    );

    $repeater->add_responsive_control(
      'field_width',
      [
        'label' => esc_html__('Width', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => ['%', 'px', 'em', 'rem', 'vw', 'custom'],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 1000,
            'step' => 1,
          ],
          '%' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
          ],
          'em' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
          ],
          'rem' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
          ],
          'vw' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $repeater->add_control(
      'checbox_style',
      [
        'label' => esc_html__('Checkbox Style ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'checkbox',
        'options' => [
          'checkbox' => esc_html__('Checkbox', 'pe-core'),
          'button' => esc_html__('Button', 'pe-core'),
        ],
        'condition' => ['field_type' => ['radio', 'checbox']],
      ]
    );

    $condi = ['field_type' => ['radio', 'checbox']];


    $repeater->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'options_typography',
        'selector' => '{{WRAPPER}} .pe--form.form--custom form .form-field{{CURRENT_ITEM}} .options--wrap label',
        'label' => esc_html__('Inputs Typography', 'pe-core'),
        'condition' => ['field_type' => ['radio', 'checbox']],
      ]
    );


    flexOptions($repeater, $condi, '{{CURRENT_ITEM}} .options--wrap', 'options', 'Options', false);



    $repeater->add_responsive_control(
      'field_padding',
      [
        'label' => esc_html__('Padding', 'pe-core'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px', 'em', 'rem', '%', 'custom'],
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}} input:not(*[type="submit"])' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
          '{{WRAPPER}} {{CURRENT_ITEM}} label:has(input[type=radio])' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
          '{{WRAPPER}} {{CURRENT_ITEM}} .select-selected' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
        ],
      ]
    );


    $repeater->add_responsive_control(
      'field_margin',
      [
        'label' => esc_html__('Margin', 'pe-core'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px', 'em', 'rem', '%', 'custom'],
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',

        ],
      ]
    );


    $repeater->add_group_control(
      \Elementor\Group_Control_Border::get_type(),
      [
        'name' => 'field_border',
        'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} input:not(*[type="submit"]) , {{WRAPPER}} {{CURRENT_ITEM}} label:has(input[type=radio]) , {{WRAPPER}} {{CURRENT_ITEM}} .select-selected',
        'important' => true
      ]
    );

    $repeater->add_responsive_control(
      'field_radius',
      [
        'label' => esc_html__('Border Radius', 'pe-core'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px'],
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}} input:not(*[type="submit"])' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;overflow: hidden',
          ' {{WRAPPER}} {{CURRENT_ITEM}} label:has(input[type=radio])' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;overflow: hidden',
          '{{WRAPPER}} {{CURRENT_ITEM}} .select-selected' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;overflow: hidden',
        ],
      ]
    );

    $repeater->end_controls_tab();

    $repeater->end_controls_tabs();


    $this->add_control(
      'form_fields',
      [
        'label' => __('Form Fields', 'pe-core'),
        'type' => Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
        'default' => [],
        'condition' => ['form_type' => 'custom_form'],
        'title_field' => '{{{ field_label }}}',
      ]
    );



    $this->add_control(
      'forms_info',
      [
        'type' => \Elementor\Controls_Manager::RAW_HTML,
        'raw' => '<div class="elementor-panel-notice elementor-panel-alert elementor-panel-alert-info">	
				   <span>This widget is only for visual preferences of the form. You can configure your form via "Contact Forms" on your admin dashboard.</span> Also do not forget to setup your servers SMTP settings before sending forms.</div>',
        'condition' => ['form_type' => 'contact_form_7'],
      ],


    );

    $forms = [];

    $forms = get_posts([
      'post_type' => 'wpcf7_contact_form',
      'numberposts' => -1
    ]);

    foreach ($forms as $form) {
      $forms[$form->ID] = $form->post_title;
    }

    $this->add_control(
      'select_form',
      [
        'label' => __('Select Form', 'pe-core'),
        'label_block' => false,
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => $forms,
        'condition' => ['form_type' => 'contact_form_7'],
      ]
    );

    $this->add_control(
      'form_layout',
      [
        'label' => esc_html__('Form Layout ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'layout--form',
        'options' => [
          'layout--form' => esc_html__('Form', 'pe-core'),
          'layout--input' => esc_html__('Single Input', 'pe-core'),
        ],
        'prefix_class' => '',
        'condition' => ['form_type' => 'contact_form_7'],
      ]
    );

    $this->add_responsive_control(
      'form_alignment',
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
        ],
        'default' => is_rtl() ? 'right' : 'left',
        'toggle' => true,
        'selectors' => [
          '{{WRAPPER}} form.wpcf7-form' => 'text-align: {{VALUE}};',
        ],
        'condition' => ['form_type' => 'contact_form_7'],
      ]
    );

    $this->add_control(
      'focus_actions',
      [
        'label' => esc_html__('Focus Actions ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT2,
        'default' => ['focus--label'],
        'options' => [
          'focus--label' => esc_html__('Label Animate', 'pe-core'),
          'focus--outline' => esc_html__('Outline', 'pe-core'),
        ],
        'multiple' => true,
      ]
    );

    $this->add_control(
      'hide_labels',
      [
        'label' => esc_html__('Hide Labels', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'return_value' => 'labels--hidden',
        'prefix_class' => '',
        'default' => '',
      ]
    );

    $this->add_control(
      'labels_inline',
      [
        'label' => esc_html__('Labels Inline', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'return_value' => 'labels--inline',
        'prefix_class' => '',
        'default' => '',
        'condition' => ['hide_labels!' => 'labels--hidden'],
      ]
    );

    $this->add_control(
      'validate_colors',
      [
        'label' => esc_html__('Live Validation', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'return_value' => 'validate--colors',
        'prefix_class' => '',
        'default' => '',

      ]
    );

    $this->add_control(
      'google_recaptcha',
      [
        'label' => esc_html__('Google reCAPTCHA', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'return_value' => 'true',
        'default' => '',
        'condition' => [
          'form_type' => 'custom_form'
        ],

      ]
    );

    $this->add_control(
      'google_recaptcha_notice',
      [
        'type' => \Elementor\Controls_Manager::RAW_HTML,
        'raw' => "<div class='elementor-panel-notice elementor-panel-alert elementor-panel-alert-info'>	
           Please make sure you've inserted your reCAPTCHA site and secret keys via <a target='_blank' href='" . admin_url('admin.php?page=pe-redux_options&tab=19') . "'><u>Theme Options > Additonal Optiopns</a></div>",
        'condition' => [
          'google_recaptcha' => 'true',
          'form_type' => 'custom_form'
        ],
      ]
    );

    $this->add_control(
      'recaptcha_version',
      [
        'label' => __('reCAPTCHA Version', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'v2',
        'options' => [
          'v2' => __('reCAPTCHA v2 (Checkbox)', 'pe-core'),
          'v3' => __('reCAPTCHA v3 (Invisible)', 'pe-core'),
        ],
        'condition' => [
          'google_recaptcha' => 'true',
          'form_type' => 'custom_form'
        ],
        'label_block' => true,
      ]
    );


    $this->add_control(
      '50_input_names',
      [
        'label' => esc_html__('Input Names (50% Width)', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'placeholder' => esc_html__('eg: your-name , your-surname ', 'pe-core'),
        'description' => esc_html__('Enter input names as you inserted them into form.', 'pe-core'),
        'ai' => false,
        'condition' => ['form_type' => 'contact_form_7'],
      ]
    );

    $this->add_control(
      '30_input_names',
      [
        'label' => esc_html__('Input Names (30% Width)', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'placeholder' => esc_html__('eg: your-name , your-surname ', 'pe-core'),
        'description' => esc_html__('Enter input names as you inserted them into form.', 'pe-core'),
        'ai' => false,
        'condition' => ['form_type' => 'contact_form_7'],
      ]
    );

    $this->end_controls_section();


    pe_button_settings($this, false, false, 'submit_button', true, 'Submit ');

    $this->start_controls_section(
      'section_email_settings',
      [
        'label' => __('E-Mail Settings', 'pe-core'),
        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
      ]
    );

    $this->add_control(
      'form_name',
      [
        'label' => __('Form Name', 'pe-core'),
        'description' => __('Will be used as sender name on "From" section of the e-mail.', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'placeholder' => __('Zeyna Contact Form', 'pe-core'),
        'label_block' => true
      ]
    );

    $this->add_control(
      'email_from',
      [
        'label' => __('E-mail From', 'pe-core'),
        'description' => __('The e-mail address which are setted up with your SMTP settings.', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'placeholder' => __('noreply@zeyna.com', 'pe-core'),
        'default' => get_option('admin_email'),
        'label_block' => true
      ]
    );

    $this->add_control(
      'email_to',
      [
        'label' => __('Send To (Recipient Email)', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'placeholder' => 'example@example.com',
        'default' => get_option('admin_email'),
        'label_block' => true
      ]
    );

    $this->add_control(
      'email_subject',
      [
        'label' => __('Email Subject', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => __('New Form Submission', 'pe-core'),
        'label_block' => true
      ]
    );

    $this->add_control(
      'email_template',
      [
        'label' => __('Email Body Template', 'pe-core'),
        'description' => __('HTML tags allows such as b,u,i,h1,h2 etc..', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXTAREA,
        'rows' => 10,
        'default' => "New Form Submission:\nName: {name}\nE-mail: {email}\nMessage: {message}",
      ]
    );


    $this->end_controls_section();

    $this->start_controls_section(
      'form_messages',
      [
        'label' => __('Field Messages', 'pe-core'),
        'condition' => ['form_type' => 'custom_form'],
      ]
    );

    $this->add_control(
      'required_field_message',
      [
        'label' => esc_html__('Required Field ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('This field is required', 'pe-core'),
        'ai' => false,
        'label_block' => true,
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'invalid_email_message',
      [
        'label' => esc_html__('Invalid E-mail ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('Invalid email address', 'pe-core'),
        'ai' => false,
        'label_block' => true,
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'invalid_tel_message',
      [
        'label' => esc_html__('Invalid Tel ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('Invalid phone number', 'pe-core'),
        'ai' => false,
        'label_block' => true,
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'invalid_number_message',
      [
        'label' => esc_html__('Invalid Number ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('Invalid number', 'pe-core'),
        'ai' => false,
        'label_block' => true,
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'invalid_url_message',
      [
        'label' => esc_html__('Invalid URL ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('Invalid URL', 'pe-core'),
        'ai' => false,
        'label_block' => true,
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'multi_select_message',
      [
        'label' => esc_html__('Select ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('Please select an option', 'pe-core'),
        'ai' => false,
        'label_block' => true,
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'required_file_message',
      [
        'label' => esc_html__('Required File ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('Please upload a file', 'pe-core'),
        'ai' => false,
        'label_block' => true,
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'required_checkbox_message',
      [
        'label' => esc_html__('Required Checbox ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('This option is required', 'pe-core'),
        'ai' => false,
        'label_block' => true,
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'invalid_date_message',
      [
        'label' => esc_html__('Invalid date ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('Invalid date', 'pe-core'),
        'ai' => false,
        'label_block' => true,
        'frontend_available' => true,
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'submit_message_settings',
      [
        'label' => __('Submit Messages', 'pe-core'),
        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        'condition' => [
          'form_type' => 'custom_form'
        ],
      ]
    );


    $this->add_control(
      'success_style',
      [
        'label' => esc_html__('Success Message Style ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'inline',
        'render_type' => 'template',
        'options' => [
          'inline' => esc_html__('Inline', 'pe-core'),
          'popup' => esc_html__('Popup', 'pe-core'),
        ],
      ]
    );


    $this->add_control(
      'success_popup_title',
      [
        'label' => esc_html__('Success Popup Title ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('Thanks for your submission!', 'pe-core'),
        'label_block' => true,
        'condition' => [
          'success_style' => 'popup'
        ],
      ]
    );

    $this->add_control(
      'success_message',
      [
        'label' => esc_html__('Success ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('Your message has been sent succesfully.', 'pe-core'),
        'label_block' => true,
      ]
    );

    $this->add_control(
      'form_error_message',
      [
        'label' => esc_html__('Form Error ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('An error occurred while sending your message.', 'pe-core'),
        'label_block' => true,
      ]
    );

    $this->add_control(
      'recaptcha_error_message',
      [
        'label' => esc_html__('reCAPTCHA Error ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('reCAPTCHA verification failed.', 'pe-core'),
        'label_block' => true,
      ]
    );

    $this->add_control(
      'nonce_error_message',
      [
        'label' => esc_html__('Nonce Error ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('Security check failed.', 'pe-core'),
        'label_block' => true,
      ]
    );

    $this->add_control(
      'server_error_message',
      [
        'label' => esc_html__('Server Error ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('Server error. Please try again later.', 'pe-core'),
        'label_block' => true,
      ]
    );


    $this->add_control(
      'file_type_error',
      [
        'label' => esc_html__('File Type Error ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('Invalid file type: ', 'pe-core'),
        'label_block' => true,
      ]
    );

    $this->add_control(
      'file_size_error',
      [
        'label' => esc_html__('File Size Error ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('File size exceeds limit.', 'pe-core'),
        'label_block' => true,
      ]
    );


    $this->end_controls_section();

    $this->start_controls_section(
      'form_self_styles',
      [
        'label' => __('Form Styles', 'pe-core'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,

      ]
    );

    flexOptions($this, false, '.pe--form form', 'form_', 'Form', false, '.form-field');

    $this->end_controls_section();

    $this->start_controls_section(
      'form_input_styles',
      [
        'label' => __('Input Styles', 'pe-core'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,

      ]
    );

    $this->add_responsive_control(
      'inputs_text_align',
      [
        'label' => esc_html__('Text Alignment', 'pe-core'),
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
        ],
        'default' => is_rtl() ? 'right' : 'left',
        'toggle' => true,
        'selectors' => [
          '{{WRAPPER}} .pe--form form .form-field>input' => 'text-align: {{VALUE}};',
        ],
      ]
    );


    $this->add_control(
      'inputs_has_bg',
      [
        'label' => esc_html__('Background', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'return_value' => 'inputs--has--bg',
        'prefix_class' => '',
        'default' => '',
      ]
    );

    $this->add_control(
      'inputs_radio_has_bg',
      [
        'label' => esc_html__('Background (for Choices)', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'return_value' => 'radios--has--bg',
        'prefix_class' => '',
        'default' => '',
      ]
    );

    $this->add_control(
      'inputs_has_backdrop',
      [
        'label' => esc_html__('Backdrop Filter', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'return_value' => 'inputs--has--backdrop',
        'prefix_class' => '',
        'default' => '',
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'inputs_typography',
        'selector' => '{{WRAPPER}} .pe--form form.wpcf7-form.init p ,
        {{WRAPPER}} .pe--form form .form-field textarea, 
        {{WRAPPER}} .pe--form.form--custom form .form-field .options--wrap label, 
        {{WRAPPER}} .pe--form.form--custom form .form-field .pe-select, 
        {{WRAPPER}} .pe--form form .form-field>input',
        'label' => esc_html__('Inputs Typography', 'pe-core'),
      ]
    );


    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'labels_typography',
        'selector' => '{{WRAPPER}} .pe--form form .form-field label.label--main ,{{WRAPPER}} .upload--main',
        'label' => esc_html__('Labels Typography', 'pe-core'),
        'condition' => ['form_type' => 'custom_form'],

      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'error_messages_typography',
        'selector' => '{{WRAPPER}} .pe--form.form--custom form .form-field .error-message',
        'label' => esc_html__('Error Messages Typography', 'pe-core'),
        'condition' => ['form_type' => 'custom_form'],
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'submit_messages_typography',
        'selector' => '{{WRAPPER}} .pe-form--submit--message p',
        'label' => esc_html__('Submit Messages Typography', 'pe-core'),
        'condition' => ['form_type' => 'custom_form'],
      ]
    );


    $this->add_group_control(
      \Elementor\Group_Control_Border::get_type(),
      [
        'name' => 'inputs_border',
        'selector' => '{{WRAPPER}} input:not(*[type="submit"]) , {{WRAPPER}} textarea ,{{WRAPPER}} .pe-select'
      ]
    );


    $this->add_responsive_control(
      'inputs_has_border_radius',
      [
        'label' => esc_html__('Border Radius', 'pe-core'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px', 'em', 'rem', '%', 'custom'],
        'selectors' => [
          '{{WRAPPER}} input:not(*[type="submit"]) , {{WRAPPER}} textarea , {{WRAPPER}} .pe-select' => '--radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'inputs_has_padding',
      [
        'label' => esc_html__('Padding', 'pe-core'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px', 'em', 'rem', '%', 'custom'],
        'selectors' => [
          '{{WRAPPER}} .pe--form' => '--paddingTop: {{TOP}}{{UNIT}}; --paddingRight: {{RIGHT}}{{UNIT}}; --paddingBottom: {{BOTTOM}}{{UNIT}}; --paddingLeft: {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'inputs_gap',
      [
        'label' => esc_html__('Columns Gap', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'em', 'vh', 'rem'],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 1000,
            'step' => 1,
          ],
          '%' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
          ],
          'em' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
          ],
          'vh' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
          ],
          'rem' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .pe--form form.wpcf7-form.init' => 'column-gap: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}} .pe--form.form--custom form' => 'column-gap: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'inputs_gap_row',
      [
        'label' => esc_html__('Rows Gap', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'em', 'vh', 'rem'],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 1000,
            'step' => 1,
          ],
          '%' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
          ],
          'em' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
          ],
          'vh' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
          ],
          'rem' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .pe--form form.wpcf7-form.init' => 'row-gap: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}} .pe--form.form--custom form' => 'row-gap: {{SIZE}}{{UNIT}};',
        ],
      ]
    );


    $this->end_controls_section();

    $this->start_controls_section(
      'submit_button_style_sec',
      [
        'label' => __('Button Styles', 'pe-core'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,

      ]
    );

    pe_button_style_settings($this, 'Submit Button', 'submit_button', false, false);

    objectAbsolutePositioning($this, '.pe--form--button--wrap', 'pe_submit_button', 'Button');


    $this->end_controls_section();


    $this->start_controls_section(
      'success_popup_styles',
      [
        'label' => __('Success Popup Styles', 'pe-core'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        'condition' => [
          'form_type' => 'custom_form',
          'success_style' => 'popup'
        ],

      ]
    );

    $this->add_control(
      'preview_success',
      [
        'label' => esc_html__('Preview?', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'prefix_class' => '',
        'return_value' => 'popup--preview',
        'default' => '',
      ]
    );


    $this->add_control(
      'success_popup_has_bg',
      [
        'label' => esc_html__('Background', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'return_value' => 'success--popup--has--bg',
        'prefix_class' => '',
        'default' => 'success--popup--has--bg',
      ]
    );

    $this->add_control(
      'success_popup_has_backdrop',
      [
        'label' => esc_html__('Backdrop Filter', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'pe-core'),
        'label_off' => esc_html__('No', 'pe-core'),
        'return_value' => 'success--popup--has--backdrop',
        'prefix_class' => '',
        'default' => '',
      ]
    );

    $this->add_control(
      'success_popup_has_backdrop_color',
      [
        'label' => esc_html__('Cakdrop Color', 'pe-core'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .pe--form--success--popup' => 'background-color: {{VALUE}}',
        ],
        'condition' => [
          'success_popup_has_backdrop' => 'success--popup--has--backdrop',
        ],
      ]
    );


    $this->add_responsive_control(
      'success_popup_backdrop_blur',
      [
        'label' => esc_html__('Bluriness', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => ['px'],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
          ],
        ],
        'default' => [
          'unit' => 'px',
          'size' => 10,
        ],
        'condition' => [
          'success_popup_has_backdrop' => 'success--popup--has--backdrop',
        ],
        'selectors' => [
          '{{WRAPPER}} .pe--form--success--popup' => '--blur: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'success_popup_icon_size',
      [
        'label' => esc_html__('Icon Size', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => ['px'],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 1000,
            'step' => 1,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .pe--form--success--popup svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}',
        ],
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'success_popup_title_typography',
        'selector' => '{{WRAPPER}} .pe--form--success--popup h3.form-success-title',
        'label' => esc_html__('Title Typography', 'pe-core'),
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'success_popup_text_typography',
        'selector' => '{{WRAPPER}} .pe--form--success--popup p',
        'label' => esc_html__('Text Typography', 'pe-core'),
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Border::get_type(),
      [
        'name' => 'success_popup_border',
        'selector' => '{{WRAPPER}} .pe--form--success--popup '
      ]
    );

    $this->add_responsive_control(
      'success_popup_has_border_radius',
      [
        'label' => esc_html__('Border Radius', 'pe-core'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px', 'em', 'rem', '%', 'custom'],
        'selectors' => [
          '{{WRAPPER}} .pe--form--success--popup' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'success_popup_has_padding',
      [
        'label' => esc_html__('Padding', 'pe-core'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px', 'em', 'rem', '%', 'custom'],
        'selectors' => [
          '{{WRAPPER}} .pe--form--success--popup' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );


    $this->add_responsive_control(
      'success_popup_gap',
      [
        'label' => esc_html__('Items Gap', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'em', 'vh', 'rem'],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 1000,
            'step' => 1,
          ],
          '%' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
          ],
          'em' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
          ],
          'vh' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
          ],
          'rem' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .pe--form--success--popup' => 'gap: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'form_button_styles',
      [
        'label' => __('Button Styles', 'pe-core'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        'condition' => ['form_type' => 'contact_form_7'],
      ]
    );


    $this->add_control(
      'submit_style',
      [
        'label' => esc_html__('Submit Style ', 'pe-core'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'text',
        'render_type' => 'template',
        'options' => [
          'text' => esc_html__('Text', 'pe-core'),
          'text--icon' => esc_html__('Text + Icon', 'pe-core'),
          'icon' => esc_html__('Icon', 'pe-core'),
        ],
        'prefix_class' => 'submit--style--',

      ]
    );

    $this->add_control(
      'submit__icon',
      [
        'label' => esc_html__('Icon', 'pe-core'),
        'type' => \Elementor\Controls_Manager::ICONS,
        'condition' => ['submit_style' => ['text--icon', 'icon']],
      ]
    );

    $this->add_control(
      'icon_position',
      [
        'label' => esc_html__('Icon Position', 'pe-core'),
        'type' => \Elementor\Controls_Manager::CHOOSE,
        'options' => [
          'left' => [
            'title' => esc_html__('Left', 'pe-core'),
            'icon' => 'eicon-h-align-left',
          ],
          'right' => [
            'title' => esc_html__('Row', 'pe-core'),
            'icon' => 'eicon-h-align-right',
          ],
        ],
        'default' => is_rtl() ? 'left' : 'right',
        'prefix_class' => 'icon--pos--',
        'toggle' => false,
        'condition' => ['submit_style' => 'text--icon'],

      ]
    );




    // $this->add_responsive_control(
    //   'button_text_alignment',
    //   [
    //     'label' => esc_html__('Text Align', 'pe-core'),
    //     'type' => \Elementor\Controls_Manager::CHOOSE,
    //     'options' => [
    //       'left' => [
    //         'title' => esc_html__('Left', 'pe-core'),
    //         'icon' => 'eicon-text-align-left',
    //       ],
    //       'center' => [
    //         'title' => esc_html__('Center', 'pe-core'),
    //         'icon' => 'eicon-text-align-center'
    //       ],
    //       'right' => [
    //         'title' => esc_html__('Right', 'pe-core'),
    //         'icon' => 'eicon-text-align-right',
    //       ],
    //     ],
    //     'default' => is_rtl() ? 'right' : 'left',
    //     'toggle' => true,
    //     'selectors' => [
    //       '{{WRAPPER}} p:has(input[type="submit"])' => 'text-align: {{VALUE}};',
    //     ],
    //   ]
    // );

    // $this->add_control(
    //   'button_main_color',
    //   [
    //     'label' => esc_html__('Button Color', 'pe-core'),
    //     'type' => \Elementor\Controls_Manager::COLOR,
    //     'selectors' => [
    //       '{{WRAPPER}} p:has(input[type="submit"])' => '--mainColor: {{VALUE}}',
    //     ]
    //   ]
    // );


    objectStyles($this, 'form_button', 'Button', '.pe--form form.wpcf7-form.init input[type="submit"]', true, false, false);
    objectAbsolutePositioning($this, '.pe--form form.wpcf7-form.init p:has(input[type="submit"])', 'form_button', 'Button');

    $this->end_controls_section();

    $this->start_controls_section(
      'form_blocks_styles',
      [
        'label' => __('Blocks Styles', 'pe-core'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        'condition' => ['form_type' => 'contact_form_7'],
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'form_blocks_typography',
        'selector' => '{{WRAPPER}} .pe--form form.wpcf7-form.init p:not(:has(input[type="submit"]))',
        'label' => esc_html__('Blocks Typography', 'pe-core'),
      ]
    );


    $this->add_group_control(
      \Elementor\Group_Control_Border::get_type(),
      [
        'name' => 'form_blocks_border',
        'selector' => '{{WRAPPER}} .pe--form form.wpcf7-form.init p:not(:has(input[type="submit"]))'
      ]
    );

    $this->add_responsive_control(
      'form_blocks_border_radius',
      [
        'label' => esc_html__('Border Radius', 'pe-core'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px', 'em', 'rem', '%', 'custom'],
        'selectors' => [
          '{{WRAPPER}} .pe--form form.wpcf7-form.init p:not(:has(input[type="submit"]))' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'form_blocks_padding',
      [
        'label' => esc_html__('Padding', 'pe-core'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px', 'em', 'rem', '%', 'custom'],
        'selectors' => [
          '{{WRAPPER}} .pe--form form.wpcf7-form.init p:not(:has(input[type="submit"]))' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();
    pe_cursor_settings($this);
    pe_color_options($this);


  }

  protected function render()
  {
    $settings = $this->get_settings_for_display();
    $option = get_option('pe-redux');
    $type = $settings['form_type'];

    $classes = [];
    if (is_array($settings['focus_actions'])) {

      foreach ($settings['focus_actions'] as $act) {
        $classes[] = $act;
      }
    }

    if ($type === 'contact_form_7') {

      $id = $settings['select_form'];

      $inputs50 = explode(' , ', $settings['50_input_names']);
      $inputs30 = explode(' , ', $settings['30_input_names']);

      ?>

      <style>
        <?php

        if (!empty($inputs50)) {
          foreach ($inputs50 as $name) {
            echo '.elementor-element-' . $this->get_id() . ' .pe--form p:has([name="' . $name . '"]) { width: 48% !important}';
          }
        }
        ;

        if (!empty($inputs30)) {
          foreach ($inputs30 as $name) {
            echo '.elementor-element-' . $this->get_id() . ' .pe--form p:has([name="' . $name . '"]) { width: 31% !important}';
          }
        }
        ;

        ?>
      </style>

      <div class="pe--form <?php echo implode(' ', $classes) ?>">

        <?php

        if ($settings['submit_style'] === 'icon' || $settings['submit_style'] === 'text--icon') {

          if ($settings['submit__icon']['value']) {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['submit__icon'], ['aria-hidden' => 'true']);
            $object = ob_get_clean();

          } else {
            $svgPath = plugin_dir_path(__FILE__) . '../assets/img/send.svg';
            $object = file_get_contents($svgPath);
          }
          ?>
          <span class="zeyna--form--submit--icon"><?php echo $object ?></span>

        <?php }

        ?>

        <?php echo do_shortcode('[contact-form-7 id="' . $id . '"]'); ?>

      </div>
      <?php


    } else if ($type === 'custom_form') {


      $recaptchaVer = '';
      $recaptchaKey = '';

      if ($settings['google_recaptcha'] === 'true') {
        $recaptchaVer = $settings['recaptcha_version'];
        $recaptchaKey = isset($option['recaptcha_site_key']) ? $option['recaptcha_site_key'] : '';

        if ($recaptchaVer === 'v2') {
          wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js', [], null, true);
        } else {
          wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js?render=' . $recaptchaKey . '', [], null, true);
        }

      }

      $this->add_render_attribute(
        'form_attributes',
        [
          'data-recaptcha-version' => $recaptchaVer,
          'data-recaptcha-site-key' => $recaptchaKey,
          'data-post-id' => get_the_ID(),
        ]
      );

      $form_attributes = $this->get_render_attribute_string('form_attributes');

      ?>

        <div class="form--preview"></div>

        <div class="pe--form form--custom <?php echo implode(' ', $classes) ?>">

          <form method="post" class="custom-form-widget-form" <?php echo $form_attributes ?>>

            <?php
            if (!empty($settings['form_fields'])) {
              foreach ($settings['form_fields'] as $key => $field) {

                $name = esc_attr($field['field_name']);
                $label = esc_html($field['field_label']);
                $placeholder = esc_html($field['field_placeholder']);
                $default = esc_html($field['field_default_value']);
                $isRequired = $field['is_required'] ? 'aria-required=true' : '';
                $type = $field['field_type'];
                $checkboxStyle = $type === 'radio' || $type === 'checkbox' ? 'checkbox--' . $field['checbox_style'] : '';

                echo '<div class="form-field elementor-repeater-item-' . $field['_id'] . ' ' . $checkboxStyle . '">';

                if ($type !== 'file' && !empty($field['field_label'])) {
                  echo "<label class='label--main' for='{$name}'>{$label}</label>";
                }

                if ($type === 'textarea') {

                  $rows = $field['textarea_rows']['size'];
                  echo "<textarea rows='{$rows}' {$isRequired} placeholder='{$placeholder}'name='{$name}' id='{$name}'>{$default}</textarea>";

                } else if ($type === 'radio') {

                  $options = explode("\n", $field['field_options']);
                  echo '<div class="options--wrap">';
                  foreach ($options as $option) {
                    $option = trim($option);
                    if (!$option)
                      continue;
                    $option_value = esc_attr($option);

                    echo "<label><input type='radio' name='{$name}' value='{$option_value}' {$isRequired}> {$option}</label>";

                  }
                  echo '</div>';

                } else if ($type === 'select') {
                  echo '<div class="pe-select">';
                  echo "<select name='{$name}' id='{$name}' {$isRequired}>";
                  $options = explode("\n", $field['field_options']);
                  foreach ($options as $option) {
                    $option = trim($option);
                    if (!$option)
                      continue;
                    $option_value = esc_attr($option);
                    echo "<option value='{$option_value}'>{$option}</option>";
                  }
                  echo "</select>";
                  echo '</div>';

                } else if ($type === 'checkbox') {
                  $option = trim($field['field_options']);
                  if ($option) {
                    $option_value = esc_attr($option);
                    echo '<div class="options--wrap">';
                    echo "<label><input type='checkbox' name='{$name}' value='{$option_value}'> {$option}</label>";
                    echo '</div>';
                  }
                } else if ($type === 'file') {
                  $file_types = !empty($field['file_types']) ? esc_attr($field['file_types']) : '.jpg, .jpeg, .png, .ai, .psd, .pdf, .eps';
                  $max_size = !empty($field['max_file_size']) ? intval($field['max_file_size']) : 5;

                  $multiple = $field['allow_multiple_files'];
                  $file_input_id = 'file--input-' . uniqid();

                  echo '<div class="zeyna--file--upload" data-file-wrapper data-file-types="' . $file_types . '" data-max-size="' . $max_size . '">';
                  echo "<input type='file' name='{$name}[]' accept='{$file_types}' id='{$file_input_id}' class='inputfile' data-multiple-caption='{count} files selected' {$multiple} {$isRequired} />";
                  echo "<label for='{$file_input_id}'><span></span>";

                  $svg_path = get_template_directory() . '/assets/img/upload.svg';
                  if (file_exists($svg_path)) {
                    echo file_get_contents($svg_path);
                  }

                  echo "<span class='upload--main'>{$label}</span>";
                  echo "</label>";

                  echo "</div>";
                  echo '<div class="file-preview"></div>';
                } else {
                  if ($type !== 'label') {
                    echo "<input autocomplete='{$type}' {$isRequired} placeholder='{$placeholder}' value='{$default}' type='{$type}' name='{$name}' id='{$name}'>";
                  }
                }

                echo '</div>';
              }

            }

            ?>
            <div class="pe--form--button--wrap pre--transform pe--form--button--disabled">

            <?php pe_button_render($this, false, false, 'submit_button', false); ?>

            <?php if ($settings['recaptcha_version'] === 'v2' && isset($recaptchaKey)): ?>
                <div class="g-recaptcha" data-sitekey="<?php echo esc_attr($recaptchaKey); ?>"></div>
            <?php endif; ?>

            </div>

          </form>

        <?php if ($settings['success_style'] === 'popup') {

          echo '<div class="pe--form--success--popup"><span class="popup--success--icon">'
            . file_get_contents(plugin_dir_path(__FILE__) . '../assets/img/check.svg') . '</span>
            <h3 class="form-success-title">' . $settings['success_popup_title'] . '</h3>
            <p class="no-margin">' . $settings['success_message'] . '<p>'
            . '</div>';

        } ?>

        </div>

    <?php }



  }



}
