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
        // $newEvent->end    = \Date::parse('c', $event['endDate']);
        $newEvent->className = 'jsonEvent'.$event['cssClass'];
        $newEvent->details   = strip_tags($event['details']);

        // var_dump($event);

        return $newEvent;
    }

}

