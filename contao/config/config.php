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
$GLOBALS['BE_MOD']['content']['calendar']['stylesheet'] = 'system/modules/fullcalendar/assets/be-style.css';
$GLOBALS['BE_MOD']['content']['calendar']['javascript'] = 'system/modules/fullcalendar/assets/be-style.js';
$GLOBALS['TL_HOOKS']['getAllEvents'][]                  = array('ContaoFullcalendar\EventManager', 'addEventsWithRrule');

$GLOBALS['BE_MOD']['content']['calendar']['fullcal']    = array('ContaoFullcalendar\CalendarSync', 'syncCal');
$GLOBALS['FE_MOD']['events']['fullcalendar']            = 'ContaoFullcalendar\ModuleFullCalendar';
$GLOBALS['BE_FFL']['fullcalView']                       = 'ContaoFullcalendar\FullcalViewWidget';


