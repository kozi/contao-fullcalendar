<?php

namespace ContaoFullcalendar\Modules;

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2019 Leo Feyer
 *
 * PHP version 5
 * @copyright Martin Kozianka 2014-2019 <http://kozianka.de/>
 * @author    Martin Kozianka <http://kozianka.de/>
 * @package    contao-fullcalendar
 * @license    LGPL
 * @filesource
 */

use ContaoFullcalendar\EventMapper;

/**
 * Class ModuleFullCalendar
 *
 * Front end module "fullcalendar".
 * @copyright Martin Kozianka 2014-2019 <http://kozianka.de/>
 * @author    Martin Kozianka <http://kozianka.de/>
 * @package    contao-fullcalendar
 */
class ModuleFullCalendar extends \Events
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_fullcalendar';

    public function generate()
    {
        if (TL_MODE === 'BE') {
            $objTemplate = new \BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['fullcalendar'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
            return $objTemplate->parse();
        }
        return parent::generate();
    }

    protected function compile()
    {
        global $objPage;

        $this->fullcal_viewButtons = ['month', 'agendaWeek', 'agendaDay'];

        $fullcalOptions = new \stdClass();
        $fullcalOptions->locale = $objPage->language;
        $fullcalOptions->firstDay = $this->cal_startDay;
        $fullcalOptions->aspectRatio = $this->fullcal_aspectRatio;
        $fullcalOptions->fixedWeekCount = ("1" === $this->fullcal_fixedWeekCount);
        $fullcalOptions->weekNumbers = ("1" === $this->fullcal_weekNumbers);

        if ($this->fullcal_contentHeight != "") {
            $fullcalOptions->contentHeight = $this->fullcal_contentHeight;
        }

        if ("1" === $this->fullcal_isRTL) {
            $fullcalOptions->direction = "rtl";
        }

        $fullcalOptions->headerToolbar = new \stdClass();
        $fullcalOptions->headerToolbar->start = $this->fullcal_headerToolbar_start;
        $fullcalOptions->headerToolbar->center = $this->fullcal_headerToolbar_center;
        $fullcalOptions->headerToolbar->end = $this->fullcal_headerToolbar_end;

        $arrCalendarIds = array_map('intval', deserialize($this->cal_calendar));
        $arrCalendar = [];
        $collectionCal = \CalendarModel::findMultipleByIds($arrCalendarIds);

        foreach ($collectionCal as $objCal) {
            $arrCalendar[$objCal->fullcal_alias] = (object) [
                'id' => $objCal->id,
                'title' => $objCal->title,
                'alias' => $objCal->fullcal_alias,
                'color' => deserialize($objCal->fullcal_color),
            ];

        }

        $GLOBALS["TL_JAVASCRIPT"][] = "system/modules/fullcalendar/assets/fullcalendar/main.min.js|static";
        $GLOBALS["TL_JAVASCRIPT"][] = "system/modules/fullcalendar/assets/fullcalendar/locales-all.min.js|static";
        $GLOBALS['TL_CSS'][] = "system/modules/fullcalendar/assets/fullcalendar/main.min.css|static";

        $GLOBALS["TL_JAVASCRIPT"][] = "system/modules/fullcalendar/assets/popper/popper.min.js|static";
        $GLOBALS["TL_JAVASCRIPT"][] = "system/modules/fullcalendar/assets/tippy/tippy-bundle.umd.min.js|static";

        $GLOBALS['TL_CSS'][] = "system/modules/fullcalendar/assets/tippy/themes/light-border.css|static";
        $GLOBALS['TL_CSS'][] = "system/modules/fullcalendar/assets/tippy/themes/light.css|static";
        $GLOBALS['TL_CSS'][] = "system/modules/fullcalendar/assets/tippy/themes/material.css|static";
        $GLOBALS['TL_CSS'][] = "system/modules/fullcalendar/assets/tippy/themes/translucent.css|static";

        if ($this->fullcal_wrapTitleMonth === "1") {
            $this->Template->appendStyle = join("\n", [
                ".fc-daygrid-event { display:block; white-space:normal; }",
                ".fc-daygrid-event > div { display:inline-block; }",
            ]);
        }

        if (!ctype_space($this->fullcal_options_additional)) {
            $this->Template->fullcalOptionsAdditional = trim($this->fullcal_options_additional);
        }

        $this->Template->showMenu = true;
        $this->Template->jsonArrayEvents = json_encode($this->getEventsAsPlainArray($arrCalendarIds), JSON_NUMERIC_CHECK);
        $this->Template->fullcalOptions = json_encode($fullcalOptions, JSON_NUMERIC_CHECK);
        $this->Template->arrCalendar = $arrCalendar;
    }

    private function getEventsAsPlainArray(array $arrCalendarIds)
    {
        $arrCalendar = [];
        $collectionCal = \CalendarModel::findMultipleByIds($arrCalendarIds);
        foreach ($collectionCal as $calModel) {
            $arrColor = deserialize($calModel->fullcal_color);
            if (is_array($arrColor) && strlen($arrColor[0]) > 0) {
                $calModel->fullcal_hexColor = '#' . $arrColor[0];
            }
            $arrCalendar[$calModel->id] = $calModel;
        }

        // Time range
        $jsonEventSources = new \stdClass();
        $tsStart = strtotime('-' . str_replace("_", " ", $this->fullcal_range), time());
        $tsEnd = strtotime('+' . str_replace("_", " ", $this->fullcal_range), time());
        $events = $this->getAllEvents($arrCalendarIds, $tsStart, $tsEnd);
        ksort($events);

        foreach ($events as $days) {
            foreach ($days as $keyDay => $day) {
                // $keyDay Ein Tag mit eventuell mehreren Events
                foreach ($day as $event) {
                    $calModel = $arrCalendar[$event['pid']];
                    $calAlias = $calModel->fullcal_alias;

                    if (!isset($jsonEventSources[$calAlias])) {
                        $eventSource = new \stdClass();
                        $eventSource->id = $calAlias;
                        $eventSource->hexColor = isset($calModel->fullcal_hexColor) ? $calModel->fullcal_hexColor : null;
                        $eventSource->events = [];
                        $jsonEventSources[$calObj->fullcal_alias] = $eventSource;
                    } else {
                        $eventSource = $jsonEventSources[$calObj->fullcal_alias];
                    }

                    $newEvent = EventMapper::convert($event);

                    $newEvent->calendarAlias = $calAlias;
                    $newEvent->backgroundColor = isset($calModel->fullcal_hexColor) ? $calModel->fullcal_hexColor : null;
                    $newEvent->className .= " " . $calAlias;

                    $eventSource->events[] = $newEvent;
                }
            }
        }
        return $jsonEventSources;
    }

}
