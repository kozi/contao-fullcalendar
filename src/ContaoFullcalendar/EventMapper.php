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



class EventMapper {

    public static function convert($day, array $event) {
        $newEvent = new \stdClass();

        $newEvent->id        = $event['id'];
        $newEvent->title     = \String::decodeEntities($event['title']);
        $newEvent->allDay    = ($event['addTime'] === '');

        $newEvent->start     = \Date::parse('c', $day);
        // TODO $newEvent->end    = \Date::parse('c', $event['endDate']);
        $newEvent->className = 'jsonEvent'.$event['cssClass'];
        $newEvent->details   = strip_tags($event['details']);

        // var_dump($event);

        return $newEvent;
    }

    public static function saveAsCalendarEventsModel($calendarId, \Sabre\VObject\Component\VEvent $vevent) {
        $isNew     = false;
        $eData     = static::serializeVevent($vevent);
        $start     = strtotime($eData['dtstart']);
        $end       = strtotime($eData['dtend']);
        $addTime = !(strlen($eData['dtstart']) === 10 && strlen($eData['dtstart']) === 10);



        if ($eData['uid'] && strlen($eData['uid']) > 0) {
            $eventObject = \CalendarEventsModel::findOneBy('fullcal_uid', $eData['uid']);
        }
        if ($eventObject === null) {
            $isNew               = true;
            $eventObject         = new \CalendarEventsModel();
            // Nur bei neuen Datensätzen den Teaser überschreiben
            $eventObject->teaser = $eData['description'];
        }
        else {
            $eventObject->fullcal_flagNew = false;
        }

        $eventObject->fullcal_uid  = $eData['uid'];
        $eventObject->fullcal_desc = $eData['description'];
        $eventObject->pid          = $calendarId;
        $eventObject->tstamp       = strtotime($eData['last-modified']);
        $eventObject->title        = $eData['summary'];
        $eventObject->location     = $eData['location'];
        $eventObject->published    = '1';

        $eventObject->addTime      = $addTime ? '1' : '';
        $eventObject->startDate    = $start;
        $eventObject->endDate      = $end;

        $eventObject->startTime    = $start;
        $eventObject->endTime      = $end;

        if(\Date::parse('dmY',$start) === \Date::parse('dmY',$end)) {
            $eventObject->endDate = null;
        }

        if (!$addTime) {
            if (\Date::parse('dmY',strtotime('+ 1 day', $start)) === \Date::parse('dmY',$end)) {
                $eventObject->endDate = null;
            }
            $eventObject->endDate = strtotime('- 1 day', $end);
        }

        // Repeat
        $eventObject->fullcal_recurrence_id = ($eData['recurrence-id']) ? $eData['recurrence-id'] : '';
        if ($eData['rrule']) {
            $rrule = $eData['rrule'];
            $eventObject->recurring     = 0;
            $eventObject->repeatEnd     = ($rrule['until']) ? strtotime($rrule['until']) : 2145913200;
            $eventObject->recurrences   = ($rrule['count']) ? intval($rrule['count']) : 0;
            $eventObject->fullcal_rrule = static::rruleToString($rrule);
        }
        else {
            $eventObject->recurring     = 0;
            $eventObject->fullcal_rrule = '';
        }
        $eventObject->save();

        // After first save() because the id is necessary for alias generation
        static::generateAlias($eventObject);

        // Must be last because of Database statement updating the values
        static::addDateTime($eventObject);
        $eventObject->fullcal_flagNew = $isNew;
        return $eventObject;
    }



    private static function addRepeat(&$eventObj, $eData) {

        $eventObj->recurring   = '';
        $eventObj->repeatEach  = '';
        $eventObj->repeatEnd   = 0;
        $eventObj->recurrences = 0;

        // $GLOBALS['TL_HOOKS']['getAllEvents']
        // parse repeat rule
        // var_dump($eData);
    }


    private static function addDateTime($eventObj) {
        $dc = new \DC_Table('tl_calendar_events');
        $dc->__set('id', $eventObj->id);
        $dc->__set('activeRecord', $eventObj);

        $tle = new \tl_calendar_events();
        $tle->adjustTime($dc);
    }

    private static function generateAlias(\CalendarEventsModel $eventObj) {
        $strAlias = standardize(\String::restoreBasicEntities($eventObj->title));

        $objAlias = \Database::getInstance()->prepare("SELECT id FROM tl_calendar_events WHERE alias=?")
            ->execute($strAlias);
        if ($objAlias->numRows > 1) {
            $strAlias .= '-'.$eventObj->id;
        }

        $eventObj->alias = $strAlias;
        $eventObj->save();
    }

    private static function serializeVevent(\Sabre\VObject\Component\VEvent $vevent) {
        $values  = array();
        $jsonObj = $vevent->jsonSerialize();
        foreach($jsonObj[1] as $arrAttr) {
            if (count($arrAttr) === 4) {
                $key          = $arrAttr[0];
                $values[$key] = $arrAttr[3];
            }
        }
        return $values;
    }


    public static function rruleToString(array $arrRrule) {
        $strRule = '';
        foreach($arrRrule as $k => $v) {
            $strRule .= strtoupper($k.'='.$v.';');
        }
        return $strRule;
    }
}

