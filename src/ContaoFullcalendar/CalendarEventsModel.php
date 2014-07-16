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

namespace ContaoFullcalendar;

class CalendarEventsModel extends \Contao\CalendarEventsModel {


    public static function findCurrentByPidWithRrule(array $arrPids, $intStart, $intEnd, array $arrOptions=array()) {
        $t          = static::$strTable;
        $intEnd     = intval($intEnd);
        $arrColumns = array("$t.pid in (".implode(',', $arrPids).") AND ($t.fullcal_rrule != '' AND $t.startTime<=$intEnd)");

        if (!BE_USER_LOGGED_IN) {
            $time = time();
            $arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
        }

        if (!isset($arrOptions['order'])) {
            $arrOptions['order']  = "$t.startTime";
        }

        return static::findBy($arrColumns, $arrOptions);
    }


}