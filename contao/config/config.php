<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2014 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2014 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de/>
 * @package    contao-webdav
 * @license    LGPL
 * @filesource
 */

$GLOBALS['BE_MOD']['system']['webdav'] = array(
    'tables'     => array('tl_webdav', 'tl_webdav_mapping'),
    'icon'       => 'system/modules/webdav/assets/drive-network.png',
    'stylesheet' => 'system/modules/webdav/assets/backend.css',
    'test'       => array('ContaoWebdav\WebdavManager', 'testConnection'),
    'refresh'    => array('ContaoWebdav\WebdavManager', 'updateFiles')
);

