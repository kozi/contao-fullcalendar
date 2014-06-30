<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2014 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2014 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de/>
 * @package    contao-dav
 * @license    LGPL
 * @filesource
 */

$GLOBALS['TL_DCA']['tl_webdav_mapping'] = array(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ptable'                      => 'tl_webdav',
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
            'fields'                  => array('webdavPath', 'localPath'),
            'flag'                    => 1,
            'panelLayout'             => 'limit'
        ),

        'label' => array
        (
            'fields'                  => array('webdavPath', 'localPath'),
            'showColumns'             => true,
            'label_callback'          => array('tl_webdav_mapping', 'addLocalPath')
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
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_webdav_mapping']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_webdav_mapping']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{webdav_legend}, webdavPath, localPath',
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid' => array
        (
            'label'                   => array('PID'),
            'search'                  => false,
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'tstamp' => array
        (
            'label'                   => array('TSTAMP'),
            'flag'                    => 9,
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'webdavPath' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_webdav_mapping']['webdavPath'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true),
            'sql'                     => "varchar(255) NOT NULL default ''",
        ),
        'localPath' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_webdav_mapping']['localPath'],
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => array('mandatory'=> true, 'multiple' => false, 'fieldType' => 'radio', 'files' => false, 'filesOnly' => false),
            'sql'                     => "blob NULL",
        ),

        'overwrite' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_webdav_mapping']['overwrite'],
            'default'                 => 1,
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
    )
);

class tl_webdav_mapping extends Backend {

    public function addLocalPath($row, $label, DataContainer $dc, $args = null) {
        $objFile = FilesModel::findByUuid($row['localPath']);
        if ($objFile !== null) {
            $args[1] = $objFile->path;
        }
        return $args;
    }

}