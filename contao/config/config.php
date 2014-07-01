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

$GLOBALS['BE_MOD']['system']['dav'] = array(
    'tables'     => array('tl_fullcalendar_dav', 'tl_fullcalendar_mapping'),
    'icon'       => 'system/modules/fullcalendar/assets/drive-network.png',
    'stylesheet' => 'system/modules/fullcalendar/assets/backend.css',
    'test'       => array('ContaoFullcalendar\WebdavManager', 'testConnection'),
    'refresh'    => array('ContaoFullcalendar\WebdavManager', 'updateFiles')
);


$GLOBALS['FE_MOD']['events']['fullcalendar'] = 'ContaoFullcalendar\ModuleFullCalendar';

