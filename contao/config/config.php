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

$GLOBALS['TL_CRON']['hourly'][]                         = array('ContaoFullcalendar\CalendarSync', 'syncCal');
$GLOBALS['TL_CRON']['weekly'][]                         = array('ContaoFullcalendar\CalendarSync', 'clearIcsFolder');


$GLOBALS['TL_HOOKS']['replaceInsertTags'][]             = array('ContaoFullcalendar\FullCalTags', 'replaceTags');


$GLOBALS['BE_MOD']['content']['calendar']['stylesheet'] = 'system/modules/fullcalendar/assets/be-style.css';
$GLOBALS['BE_MOD']['content']['calendar']['javascript'] = 'system/modules/fullcalendar/assets/be-style.js';

$GLOBALS['BE_MOD']['content']['calendar']['fullcal']    = array('ContaoFullcalendar\CalendarSync', 'syncOneCal');
$GLOBALS['FE_MOD']['events']['fullcalendar']            = 'ContaoFullcalendar\ModuleFullCalendar';
$GLOBALS['BE_FFL']['fullcalView']                       = 'ContaoFullcalendar\FullcalViewWidget';


