<?php

/* TODO Sync Button einbauen
$GLOBALS['TL_DCA']['tl_calendar_events']['list']['global_operations']['fullcal'] = array(
    'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
    'href'                => 'http://google.de',
    'class'               => 'header_edit_all',
    'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="s"'
);*/

$GLOBALS['TL_DCA']['tl_calendar_events']['list']['sorting']['child_record_callback'] =
    array('tl_calendar_events_fullcal', 'listEvents');

$GLOBALS['TL_DCA']['tl_calendar_events']['config']['onload_callback'][] =
    array('tl_calendar_events_fullcal', 'adjustDca');

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fullcal_uid'] = array(
    'sql'  => "varchar(255) NOT NULL default ''",
    'eval' => array('doNotCopy' => true)
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fullcal_desc'] = array(
    'sql'  => "text NULL",
    'eval' => array('doNotCopy' => true)
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fullcal_rrule'] = array(
    'sql'  => "blob NULL",
    'eval' => array('doNotCopy' => true)
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fullcal_detailViewer'] = array(
    'sql'  => "char(1) NOT NULL default ''",
    'eval' => array('doNotCopy' => true)
);



class tl_calendar_events_fullcal extends tl_calendar_events {

    public function adjustDca() {
        if ('edit' === Input::get('act')) {
            $eventObj = CalendarEventsModel::findByPk(Input::get('id'));
            if ($eventObj->fullcal_uid !== '') {
                $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] =
                    '{fullcal_legend},fullcal_detailViewer;'.str_replace(
                        array(
                        '{title_legend},title,alias,author;',
                        '{date_legend},addTime,startDate,endDate;',
                        'location,',
                        '{recurring_legend},recurring;',
                        '{publish_legend},published,start,stop'
                    ),
                    '',
                    $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default']
                );


            }
       }

    }

    public function listEvents($arrRow) {

        // TODO Buttons mit CSS und Javascript ausblenden
        $strReturn = parent::listEvents($arrRow);
        if (strlen($arrRow['fullcal_uid']) > 0) {
            $strReturn = '<div class="fullcal_sync">'.$strReturn.'</div>';
        }
        return $strReturn;
    }
}