<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2017 Leo Feyer
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2014-2017 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de/>
 * @package    contao-fullcalendar
 * @license    LGPL
 * @filesource
 */

$GLOBALS['TL_DCA']['tl_calendar_events']['list']['sorting']['child_record_callback'] = ['tl_calendar_events_fullcal', 'listEvents'];
$GLOBALS['TL_DCA']['tl_calendar_events']['config']['onload_callback'][]              = ['tl_calendar_events_fullcal', 'adjustDca'];

// Fields
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fullcal_uid'] = [
    'label'=> &$GLOBALS['TL_LANG']['tl_calendar_events']['fullcal_uid'],
    'sql'  => "varchar(255) NOT NULL default ''",
    'eval' => ['doNotCopy' => true]
];

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fullcal_id'] = [
    'label'=> &$GLOBALS['TL_LANG']['tl_calendar_events']['fullcal_id'],
    'sql'  => "varchar(255) NOT NULL default ''",
    'eval' => ['doNotCopy' => true]
];

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fullcal_desc'] = [
    'label'=> &$GLOBALS['TL_LANG']['tl_calendar_events']['fullcal_desc'],
    'sql'  => "text NULL",
    'eval' => ['doNotCopy' => true]
];

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fullcal_ics'] = [
    'label'=> &$GLOBALS['TL_LANG']['tl_calendar_events']['fullcal_ics'],
    'sql'  => "varchar(255) NOT NULL default ''",
    'eval' => ['doNotCopy' => true]
];

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fullcal_cat'] = [
    'label'=> &$GLOBALS['TL_LANG']['tl_calendar_events']['fullcal_cat'],
    'sql'  => "varchar(255) NOT NULL default ''",
    'eval' => ['doNotCopy' => true]
];

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fullcal_detailViewer'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['fullcal_detailViewer'],
    'inputType' => 'fullcalView',
    'sql'       => "char(1) NOT NULL default ''",
    'eval'      => ['doNotCopy' => true]
];

class tl_calendar_events_fullcal extends tl_calendar_events
{
    public function adjustDca()
    {
        if ($calObj = \CalendarModel::findByPk(\Input::get('id')))
        {
            if ($calObj->fullcal_type !== '')
            {
                $GLOBALS['TL_DCA']['tl_calendar_events']['list']['global_operations']['fullcal'] = [
                    'label'               => &$GLOBALS['TL_LANG']['tl_calendar_events']['fullcal_syn'],
                    'href'                => 'key=fullcal',
                    'class'               => 'header_sync',
                    'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="s"'
                ];
            }
        }

        if ('edit' === Input::get('act'))
        {
            $eventObj = CalendarEventsModel::findByPk(Input::get('id'));
            if ($eventObj && $eventObj->fullcal_uid !== '')
            {
                $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] =
                    str_replace(
                        [
                            '{details_legend},',
                            '{title_legend},title,alias,author;',
                            '{date_legend},addTime,startDate,endDate;',
                            'location,',
                            '{recurring_legend},recurring;',
                            '{publish_legend},published,start,stop'
                        ],
                        ['{details_legend},fullcal_detailViewer,',''],
                        $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default']
                    );
            }
        }

    }

    public function listEvents($arrRow)
    {
        $strReturn = parent::listEvents($arrRow);
        if (strlen($arrRow['fullcal_uid']) > 0)
        {
            $strReturn = '<div class="fullcal_sync">'.$strReturn.'</div>';
        }
        return $strReturn;
    }
}

