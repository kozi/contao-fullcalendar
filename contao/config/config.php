<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2016 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2014-2016 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de/>
 * @package    contao-fullcalendar
 * @license    LGPL
 * @filesource
 */

$GLOBALS['TL_CRON']['hourly'][]                         = ['ContaoFullcalendar\CalendarSync', 'syncCal'];
$GLOBALS['TL_CRON']['weekly'][]                         = ['ContaoFullcalendar\CalendarSync', 'clearIcsFolder'];

$GLOBALS['TL_HOOKS']['replaceInsertTags'][]             = ['ContaoFullcalendar\FullCalTags', 'replaceTags'];

$GLOBALS['BE_MOD']['content']['calendar']['stylesheet'] = 'system/modules/fullcalendar/assets/be-style.css';
$GLOBALS['BE_MOD']['content']['calendar']['javascript'] = 'system/modules/fullcalendar/assets/be-style.js';

$GLOBALS['BE_MOD']['content']['calendar']['fullcal']    = ['ContaoFullcalendar\CalendarSync', 'syncOneCal'];
$GLOBALS['FE_MOD']['events']['fullcalendar']            = 'ContaoFullcalendar\ModuleFullCalendar';
$GLOBALS['BE_FFL']['fullcalView']                       = 'ContaoFullcalendar\FullcalViewWidget';

