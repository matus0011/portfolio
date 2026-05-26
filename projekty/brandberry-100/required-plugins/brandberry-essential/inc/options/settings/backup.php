<?php

// backup option
CSF::createSection(BRANDBERRY_ESSENTIAL_OPTION_KEY, array(

    'title'  => esc_html__('Backup Options', 'brandberry'),
    'icon'   => 'fa fa-share-square-o',
    'fields' => array(
        array(
            'id'    => 'backup_options',
            'type'  => 'backup',
            'title' => esc_html__('Backup Your All Options', 'brandberry'),
            'desc'  => esc_html__('If you want to take backup your option you can backup here.', 'brandberry'),
        ),
    ),
));
