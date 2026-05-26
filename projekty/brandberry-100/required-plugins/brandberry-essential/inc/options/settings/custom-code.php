<?php 

  // custom css
  CSF::createSection( 'brandberry_settings', array(
    'title'  => esc_html__( 'Custom Code', 'brandberry-essential' ),
    'icon'   => 'fa fa-code',
    'fields' => array(
    
          array(
            'id'            => 'opt-tabbed-code',
            'type'          => 'tabbed',
            'title'         => esc_html__('Custom Code','brandberry-essential'),
            'tabs'          => array(
              array(
                'title'     => 'Css',
                'icon'      => 'fa fa-css3',
                'fields'    => array(                    
                      array(
                        'id'       => 'custom_css',
                        'type'     => 'code_editor',
                        'title'    => esc_html__('Desktop Device','brandberry-essential'),
                        'settings' => array(
                          'theme'  => 'mbo',
                          'mode'   => 'css',
                        ),
                        'default'  => '',
                      ),
                    
                     array(
                        'id'       => 'custom_css_tab',
                        'type'     => 'code_editor',
                        'title'    => esc_html__('Tab Device','brandberry-essential'),
                        'help' => esc_html__('Max width 991','brandberry-essential'),
                        'settings' => array(
                          'theme'  => 'mbo',
                          'mode'   => 'css',
                        ),
                        'default'  => '',
                      ),
                    
                     array(
                        'id'       => 'custom_css_mobile',
                        'type'     => 'code_editor',
                        'title'    => esc_html__('Mobile Device','brandberry-essential'),
                        'settings' => array(
                          'theme'  => 'mbo',
                          'mode'   => 'css',
                        ),
                        'default'  => '.element{ color: #ffbc00; }',
                      ),
                )
              ),
              array(
                'title'     => 'JS',
                'icon'      => 'fa fa-gear',
                'fields'    => array(
                    array(
                        'id'       => 'opt_code_editor_js',
                        'type'     => 'code_editor',
                        'title'    => esc_html__('Javascript Editor','brandberry-essential'),
                        'settings' => array(
                          'theme'  => 'monokai',
                          'mode'   => 'javascript',
                        ),
                        'default'  => '',
                    ),
                )
              ),
            )
          ),         
          
    ),
  ) ); 