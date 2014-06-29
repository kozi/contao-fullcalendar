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

$GLOBALS['BE_MOD']['system']['dav'] = array(
    'tables'     => array('tl_dav'),
    'icon'       => 'system/modules/dav/assets/icon.png',
    'stylesheet' => 'system/modules/dav/assets/backend.css',
    'test'       => array('ContaoDav\DavManager', 'testConnection')
);

