<?php

namespace ContaoFullcalendar;

/**
 * class EventMapper
 *
 * Contao Open Source CMS
 * Copyright (C) 2005-2019 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright Martin Kozianka 2014-2019 <http://kozianka.de/>
 * @author    Martin Kozianka <http://kozianka.de/>
 * @package    contao-fullcalendar
 * @license    LGPL
 * @filesource
 */

use Contao\CalendarEventsModel;
use Contao\File;
use Contao\StringUtil;
use Sabre\VObject\Component\VCalendar;
use Sabre\VObject\Node;

class EventMapper
{
    /**
     * Convert "Contao-Event-Array" to json representation for fullcalendar
     * @param array
     * @param \CalendarModel
     * @return object
     */
    public static function convert(array $event, $calObj)
    {
        $arrCSS = array_map('trim', explode(' ', $event['class']));
        $arrCSS[] = 'jsonEvent';
        $newEvent = new \stdClass();
        $newEvent->id = $event['id'];
        $newEvent->pid = $event['pid'];
        $newEvent->alias = $event['alias'];

        $newEvent->title = StringUtil::decodeEntities($event['title']);
        $newEvent->details = (array_key_exists("details", $event) && is_string($event['details'])) ? strip_tags($event['details']) : null;

        $newEvent->author = $event['author'];
        $newEvent->teaser = $event['teaser'];
        $newEvent->location = $event['location'];
        $newEvent->href = $event['href'];

        $dateBegin = \Date::parse('Y-m-d', $event['begin']);
        $dateEnd = \Date::parse('Y-m-d', $event['end']);

        $timeBegin = \Date::parse('H:i', $event['begin']);
        $timeEnd = \Date::parse('H:i', $event['end']);

        if ($event['fullcal_cat']) {
            $arrCSS[] = 'cat_' . standardize($event['fullcal_cat']);
        }

        if ($event['addTime'] === '') {
            // Ohne Zeitangaben
            $newEvent->start = $dateBegin;
            // Ein oder mehrere Tage?
            if ($dateBegin !== $dateEnd) {
                // Es muss 1 Tag hinzugefügt werden
                $newEvent->end = \Date::parse('Y-m-d', strtotime('+1 day', $event['end']));
                $arrCSS[] = 'days';
            } else {
                $arrCSS[] = 'oneDay';
            }
        } elseif ($event['begin'] === $event['end']) {
            // Ein Event mit Startzeit ohne Endzeit
            $newEvent->start = \Date::parse('c', $event['begin']);
            $arrCSS[] = 'oneDayTime';
        } elseif ($timeBegin === $timeEnd) {
            // Nur eine Startzeit
            $newEvent->start = \Date::parse('c', $event['begin']);
            $newEvent->end = $dateEnd;
            $arrCSS[] = 'daysStart';
        } else {
            // Mehrere Tage mit Start- und Endzeit
            $newEvent->start = \Date::parse('c', $event['begin']);
            $newEvent->end = \Date::parse('c', $event['end']);
            $arrCSS[] = 'daysTime';
        }

        if ($calObj !== null) {
            // Add calendar alias as css class
            $arrCSS[] = $calObj->fullcal_alias;
            // Add color from calendar Object
            if ($calObj->fullcal_hexColor) {
                $newEvent->backgroundColor = $calObj->fullcal_hexColor;
            }
            // Add calendar alias as attribute
            $newEvent->calendarAlias = $calObj->fullcal_alias;
        }

        $tmpl = new \FrontendTemplate("fullcal_description");
        foreach ($event as $k => $v) {
            $tmpl->$k = $v;
        }

        $newEvent->description = $tmpl->parse();
        $newEvent->className = implode(' ', $arrCSS);

        return $newEvent;
    }

    /**
     * Get CalendarEventsModel from VEvent
     * @param \Sabre\VObject\Node $vevent
     * @param \Model $calObj
     * @return \CalendarEventsModel
     */
    public static function getCalendarEventsModel(Node $vevent, \Model $calObj, \DateTimeZone $objTimezone)
    {
        $eData = static::serializeVevent($vevent);
        $objTimestamp = $vevent->DTSTAMP->getDateTime();
        $objStartDate = $vevent->DTSTART->getDateTime();
        $objEndDate = $vevent->DTEND->getDateTime();
        $objStartDate->setTimezone($objTimezone);
        $objEndDate->setTimezone($objTimezone);
        $objTimestamp->setTimezone($objTimezone);

        // Nur wenn Start UND ENDE eine Zeitangabe enthalten ist der Termin mit Zeitangabe!
        $addTime = ($vevent->DTSTART->hasTime() && $vevent->DTEND->hasTime());

        $eventId = $eData['uid'] . '_' . $objStartDate->getTimestamp();
        $eventObject = CalendarEventsModel::findOneBy('fullcal_id', $eventId);

        if ($eventObject === null) {
            $isNew = true;
            $eventObject = new CalendarEventsModel();
        } else {
            $isNew = false;
            $eventObject->fullcal_flagNew = false;
        }

        $eventObject->fullcal_id = $eventId;
        $eventObject->fullcal_uid = $eData['uid'];
        $eventObject->fullcal_desc = $eData['description'];
        $eventObject->fullcal_cat = $eData['categories'];
        $eventObject->teaser = $eData['description'];
        $eventObject->title = $eData['summary'];
        $eventObject->location = $eData['location'];
        $eventObject->pid = $calObj->id;
        $eventObject->source = 'default';
        $eventObject->published = '1';
        $eventObject->addTime = $addTime ? '1' : '';
        $eventObject->tstamp = $objTimestamp->getTimestamp();
        $eventObject->startDate = $objStartDate->getTimestamp();
        $eventObject->endDate = $objEndDate->getTimestamp();

        $eventObject->startTime = $objStartDate->getTimestamp();
        $eventObject->endTime = $objEndDate->getTimestamp();

        if ($objStartDate->format('dmY') === $objEndDate->format('dmY')) {
            $eventObject->endDate = null;
        }

        if (!$addTime) {
            // Remove time info
            $eventObject->startDate = strtotime(date("Y-m-d", $eventObject->startDate));
            $eventObject->startTime = $eventObject->startDate;

            $objIntervalOneDay = new \DateInterval('P1D');
            $objStartDate->add($objIntervalOneDay);

            if ($objStartDate->format('dmY') === $objEndDate->format('dmY')) {
                $eventObject->endDate = null;
                $eventObject->endTime = $eventObject->startDate;
            } else {
                // Bei mehrtägigen Terminen ohne Zeitangabe muss
                // der letzte Tag subtrahiert werden.
                $objEndDateSubbed = $objEndDate->sub($objIntervalOneDay);
                $eventObject->endDate = $objEndDateSubbed->getTimestamp();
                $eventObject->endTime = $objEndDateSubbed->getTimestamp();
            }
        }

        $eventObject->save();

        // After first save() because the id is necessary for alias generation
        static::generateAlias($eventObject);

        // Save the single event as ics files
        static::saveEventAsIcs($eventObject, $vevent);

        $eventObject->fullcal_flagNew = $isNew;
        return $eventObject;

    }

    /* Get a flat array with the event infos
     * @param \Sabre\VObject\Component\VEvent
     * @return array
     */
    public static function serializeVevent(Node $vevent)
    {
        $values = [];
        $jsonObj = $vevent->jsonSerialize();

        foreach ($jsonObj[1] as $arrAttr) {
            if (count($arrAttr) === 4) {
                $key = $arrAttr[0];
                $values[$key] = $arrAttr[3];
            }
        }
        return $values;
    }

    /**
     * Save one event in an ics file
     * @param CalendarEventsModel $eventObject
     * @param \Sabre\VObject\Node $vevent
     */
    private static function saveEventAsIcs(CalendarEventsModel $eventObject, Node $vevent)
    {
        // Generate a unique filename
        $strFile = CalendarSync::$icsFolder . $eventObject->alias . '.ics';

        // Create a calendar & add the event
        $cal = new VCalendar();
        $cal->add($vevent);

        // Save this calendar as an ics file
        $file = new File($strFile);
        $file->write($cal->serialize());
        $file->close();

        // Save the reference to the ics file in the event
        $eventObject->fullcal_ics = $strFile;
        $eventObject->save();
    }

    /* Generate alias for CalendarEventsModel
     * @param \CalendarEventsModel
     */
    private static function generateAlias(CalendarEventsModel $eventObj)
    {
        $strAlias = standardize(StringUtil::restoreBasicEntities($eventObj->title));

        $objAlias = \Database::getInstance()->prepare("SELECT id FROM tl_calendar_events WHERE alias=?")
            ->execute($strAlias);

        if ($objAlias->numRows > 1) {
            $strAlias .= '-' . $eventObj->id;
        }
        $eventObj->alias = $strAlias;
        $eventObj->save();
    }
}
