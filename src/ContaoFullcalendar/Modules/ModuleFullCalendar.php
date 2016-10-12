<?php

namespace ContaoFullcalendar\Modules;

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2016 Leo Feyer
 *
 * PHP version 5
 * @copyright Martin Kozianka 2014-2016 <http://kozianka.de/>
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
 * @copyright Martin Kozianka 2014-2016 <http://kozianka.de/>
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
        if (TL_MODE === 'BE')
        {
            $objTemplate           = new \BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['fullcalendar'][0]) . ' ###';
            $objTemplate->title    = $this->headline;
            $objTemplate->id       = $this->id;
            $objTemplate->link     = $this->name;
            $objTemplate->href     = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
            return $objTemplate->parse();
        }
        return parent::generate();
    }

    protected function compile()
    {
        global $objPage;

        $this->fullcal_viewButtons    = ['month', 'agendaWeek', 'agendaDay'];

        $fullcalOptions               = new \stdClass();
        $fullcalOptions->firstDay     = $this->cal_startDay;
        $fullcalOptions->weekNumbers  = $this->fullcal_weekNumbers;
        $fullcalOptions->weekMode     = $this->fullcal_weekMode;
        $fullcalOptions->aspectRatio  = $this->fullcal_aspectRatio;
        $fullcalOptions->isRTL        = ("1" === $this->fullcal_isRTL);

        $fullcalOptions->header         = new \stdClass();
        $fullcalOptions->header->left   = $this->fullcal_header_left;
        $fullcalOptions->header->center = $this->fullcal_header_center;
        $fullcalOptions->header->right  = $this->fullcal_header_right;

        $arrCalendarIds = array_map('intval', deserialize($this->cal_calendar));
        $arrCalendar    = [];
        $collectionCal  = \CalendarModel::findMultipleByIds($arrCalendarIds);

        foreach($collectionCal as $objCal)
        {
            $arrCalendar[$objCal->fullcal_alias] = (object) [
                'id'     => $objCal->id,
                'title'  => $objCal->title,
                'alias'  => $objCal->fullcal_alias,
                'color'  => deserialize($objCal->fullcal_color),
            ];

        }

        if ($objPage->hasJQuery !== '1')
        {
            $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/fullcalendar/assets/jquery/dist/jquery.min.js|static';
        }

        $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/fullcalendar/assets/moment/min/moment.min.js|static';

        $GLOBALS['TL_CSS'][]        = 'system/modules/fullcalendar/assets/fullcalendar/dist/fullcalendar.css||static';
        $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/fullcalendar/assets/fullcalendar/dist/fullcalendar.js|static';

        $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/fullcalendar/assets/fullcal-eventManager.js|static';
		
		$pathLang = 'system/modules/fullcalendar/assets/fullcalendar/dist/locale/'.$objPage->language.'.js';
        if (file_exists(TL_ROOT.'/'.$pathLang))
        {
            // Include file with translations
            $GLOBALS['TL_JAVASCRIPT'][] = $pathLang.'|static';
            // Set correct locale in fullcalendar configuration
            $fullcalOptions->locale = $objPage->language;
        }
		
        $this->Template->jsonArrayEvents = json_encode($this->getEventsAsPlainArray($arrCalendarIds), JSON_NUMERIC_CHECK);
        $this->Template->fullcalOptions  = json_encode($fullcalOptions, JSON_NUMERIC_CHECK);
        $this->Template->arrCalendar     = $arrCalendar;
    }

    private function getEventsAsPlainArray(array $arrCalendarIds)
    {
        $arrCalendar   = [];
        $collectionCal = \CalendarModel::findMultipleByIds($arrCalendarIds);
        foreach($collectionCal as $calModel)
        {
            $arrColor = deserialize($calModel->fullcal_color);
            if (is_array($arrColor) && strlen($arrColor[0]) > 0)
            {
                $calModel->fullcal_hexColor = '#'.$arrColor[0];
            }
            $arrCalendar[$calModel->id] = $calModel;
        }

        // Time range
        $jsonEvents = [];
        $tsStart    = strtotime('-'.$this->fullcal_range, time());
        $tsEnd      = strtotime('+'.$this->fullcal_range, time());
        $events     = $this->getAllEvents($arrCalendarIds, $tsStart, $tsEnd);
        ksort($events);

        foreach($events as $days)
        {
            foreach($days as $keyDay => $day)
            {
                // $keyDay Ein Tag mit eventuell mehreren Events
                foreach($day as $event)
                {
                    $jsonEvents[] = EventMapper::convert($event, $arrCalendar[$event['pid']]);
                }
            }
        }
        return $jsonEvents;
    }

}

