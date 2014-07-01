<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2014 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2014 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de/>
 * @package    contao-fullcalendar
 * @license    LGPL
 * @filesource
 */

$GLOBALS['TL_DCA']['tl_fullcalendar_dav'] = array(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ctable'                      => array('tl_fullcalendar_mapping'),
        'switchToEdit'				  => true,
        'enableVersioning'            => true,
        'sql' => array(
            'keys' => array('id' => 'primary')
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 2,
            'fields'                  => array('title DESC'),
            'flag'                    => 1,
            'panelLayout'             => 'limit'
        ),
        'label' => array
        (
            'fields'                  => array('title', 'baseUri', 'username'),
            'showColumns'             => true,
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset();"'
            )
        ),
        'operations' => array
        (
            'test' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_fullcalendar_dav']['test'],
                'href'                => 'key=test',
                'icon'                => 'system/modules/fullcalendar/assets/lightning.png',
            ),
            'refresh' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_fullcalendar_dav']['refresh'],
                'href'                => 'key=refresh',
                'icon'                => 'system/modules/fullcalendar/assets/arrow-circle-double.png',
            ),
            'mapping' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_fullcalendar_dav']['mapping'],
                'href'                => 'table=tl_fullcalendar_mapping',
                'icon'                => 'system/modules/fullcalendar/assets/gear.png'
            ),
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_fullcalendar_dav']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_fullcalendar_dav']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{webdav_legend}, title, baseUri, username, password',
    ),


    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_fullcalendar_dav']['tstamp'],
            'flag'                    => 9,
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'title' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_fullcalendar_dav']['title'],
            'exclude'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true),
            'sql'                     => "varchar(255) NOT NULL default ''",
        ),
        'baseUri' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_fullcalendar_dav']['baseUri'],
            'exclude'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true),
            'sql'                     => "varchar(255) NOT NULL default ''",
        ),
        'username' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_fullcalendar_dav']['username'],
            'exclude'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'tl_class' => 'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''",
        ),
        'password' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_fullcalendar_dav']['password'],
            'exclude'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'tl_class' => 'w50', 'encrypt' => true),
            'sql'                     => "blob NULL",
        ),

    )
);