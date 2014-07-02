<?php

$GLOBALS['TL_DCA']['tl_calendar_events']['config']['onload_callback'][] =
    array('tl_calendar_events_fullcal', 'adjustDca');

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fullcal_uid'] = array(
    'sql'  => "varchar(255) NOT NULL default ''",
    'eval' => array('doNotCopy' => true)
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['fullcal_detailViewer'] = array(
    'sql'  => "char(1) NOT NULL default ''",
    'eval' => array('doNotCopy' => true)
);



class tl_calendar_events_fullcal extends Backend {

    public function adjustDca() {
        if ('edit' === Input::get('act')) {
            $eventObj = CalendarEventsModel::findByPk(CURRENT_ID);
            if (false && $eventObj->fullcal_uid !== '') {
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
}