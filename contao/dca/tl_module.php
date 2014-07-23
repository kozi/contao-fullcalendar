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


$fullcalPalette = '{fullcal_legend},cal_calendar,fullcal_range,cal_startDay,
fullcal_weekMode,fullcal_aspectRatio,fullcal_weekNumbers,fullcal_isRTL;
fullcal_header_left,fullcal_header_center,fullcal_header_right;';

$GLOBALS['TL_DCA']['tl_module']['palettes']['fullcalendar'] = str_replace('{include_legend},form;', $fullcalPalette, $GLOBALS['TL_DCA']['tl_module']['palettes']['form']);

$GLOBALS['TL_DCA']['tl_module']['config']['onsubmit_callback'][] =
    array('tl_module_fullcal', 'checkCalNoSpan');

$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_range'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['fullcal_range'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('3 months','6 months','1 year','2 years'),
    'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
    'eval'                    => array('tl_class' => 'w50'),
    'sql'                     => "varchar(255) NOT NULL default 'next_365'",
);

$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_weekNumbers'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['fullcal_weekNumbers'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'sql'                     => "char(1) NOT NULL default ''",
    'eval'                    => array('tl_class' => 'w50'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_weekMode'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['fullcal_weekMode'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('fixed', 'liquid', 'variable'),
    'sql'                     => "varchar(255) NOT NULL default 'fixed'",
    'eval'                    => array('tl_class' => 'w50'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_aspectRatio'] = array( // 1.35
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['fullcal_aspectRatio'],
    'exclude'                 => true,
    'default'                 => '1.35',
    'inputType'               => 'text',
    'sql'                     => "varchar(255) NOT NULL default ''",
    'eval'                    => array('rgxp' => 'digit', 'tl_class' => 'w50'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_isRTL'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['fullcal_isRTL'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'sql'                     => "char(1) NOT NULL default ''",
    'eval'                    => array('tl_class' => 'w50'),
);


$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_header_left'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['fullcal_header_left'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'default'                 => 'prev,next today',
    'sql'                     => "varchar(255) NOT NULL default ''",
    'eval'                    => array('tl_class' => 'w50'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_header_center'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['fullcal_header_center'],
    'exclude'                 => true,
    'default'                 => 'title',
    'inputType'               => 'text',
    'sql'                     => "varchar(255) NOT NULL default ''",
    'eval'                    => array('tl_class' => 'w50'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_header_right'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['fullcal_header_right'],
    'exclude'                 => true,
    'default'                 => 'month,agendaWeek,agendaDay',
    'inputType'               => 'text',
    'sql'                     => "varchar(255) NOT NULL default ''",
    'eval'                    => array('tl_class' => 'w50'),
);

class tl_module_fullcal extends Backend {

    public function checkCalNoSpan(DataContainer $dc) {
        $id        = intval($dc->id);
        $moduleObj = \ModuleModel::findByPk($id);
        if($moduleObj && $moduleObj->type === 'fullcalendar') {
            $moduleObj->cal_noSpan = '1';
            $moduleObj->save();
        }
    }
}

