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

$GLOBALS['BE_MOD']['system']['webdav'] = array(
    'tables'     => array('tl_webdav', 'tl_webdav_mapping'),
    'icon'       => 'system/modules/dav/assets/drive-network.png',
    'stylesheet' => 'system/modules/dav/assets/backend.css',
    'test'       => array('ContaoDav\WebdavManager', 'testConnection'),
    'refresh'    => array('ContaoDav\WebdavManager', 'updateFiles')
);

