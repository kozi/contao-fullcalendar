<?php
/**
 * Created by PhpStorm.
 * User: kozi
 * Date: 10.07.14
 * Time: 13:05
 */

namespace ContaoFullcalendar;


class EventManager extends \Events {

    public function __construct() {

    }

    public function addEventsWithRrule($arrEvents, $arrCalendars, $intStart, $intEnd, \Module $objModule) {


        // Get events with special rrule
        $events = CalendarEventsModel::findCurrentByPidWithRrule($arrCalendars, $intStart, $intEnd);

        if ($events === null) {
            return $arrEvents;
        }

        $timezone = $GLOBALS['TL_CONFIG']['timeZone'];
        $dateFmt  = 'Y-m-d H:i:s';

        foreach ($events as $e) {
            // Parse rrules and add calculated events

            $startDate   = new \DateTime(\Date::parse($dateFmt, $e->startTime), new \DateTimeZone($timezone));
            $endDate     = ($e->endTime !== null) ? new \DateTime(\Date::parse($dateFmt, $e->endTime), new \DateTimeZone($timezone)) : null;

            $rule        = new \Recurr\Rule($e->fullcal_rrule, $startDate, $endDate, $timezone);
            $transformer = new \Recurr\Transformer\ArrayTransformer();
            $collRecurr  = $transformer->transform($rule, 100);
            $arrUrls     = array();
            foreach($collRecurr as  $recurr) {

                if (array_key_exists($e->pid, $arrUrls)) {
                    $strUrl = $arrUrls[$e->pid];
                }
                else {
                    $objCalendar = \CalendarModel::findByPk($e->pid);
                    if ($objCalendar !== null && $objCalendar->jumpTo && ($objTarget = $objCalendar->getRelated('jumpTo')) !== null) {
                        $strUrl = $this->generateFrontendUrl($objTarget->row(), (($GLOBALS['TL_CONFIG']['useAutoItem'] && !$GLOBALS['TL_CONFIG']['disableAlias']) ?  '/%s' : '/events/%s'));
                        $arrUrls[$e->pid] = $strUrl;
                    }
                }
                $intEventStart = $recurr->getStart()->getTimestamp();
                $intEventEnd   = $recurr->getEnd()->getTimestamp();

                $this->addEvent($e, $intEventStart, $intEventEnd, $strUrl, $intStart, $intEnd, $e->pid);
            }
        }

        foreach ($this->arrEvents as $key0 => $days) {
            foreach ($days as $key1 => $day) {
                foreach ($day as $event) {
                    $event['fullcal_generated'] = true;
                    $arrEvents[$key0][$key1][]  = $event;
                }
            }
        }

        foreach ($arrEvents as $key0 => &$days) {
            foreach ($days as $key1 => &$day) {
                foreach ($day as $index => &$event) {

                    if (strlen($event['fullcal_rrule']) > 0 && $event['fullcal_generated'] !== true) {
                        // Events mit rrule, die nicht in dieser Funktion erstellt wurden müssen
                        // entfernt werden, da sie sonst doppelt vorhanden sind.
                        unset($arrEvents[$key0][$key1][$index]);
                    }
                    $event['teaser'] = str_replace('{{event_description}}', $event['fullcal_desc'], $event['teaser']);
                }
            }
        }
        return $arrEvents;
    }


    protected function compile() {

    }


}