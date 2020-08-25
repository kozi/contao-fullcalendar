<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2019 Leo Feyer
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2014-2019 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de/>
 * @package    contao-fullcalendar
 * @license    LGPL
 * @filesource
 */

$fullcalPalette = '{fullcal_legend},cal_calendar,fullcal_range,cal_startDay,fullcal_weekNumbers,fullcal_fixedWeekCount;
fullcal_contentHeight,fullcal_aspectRatio,fullcal_wrapTitleMonth,fullcal_isRTL;
fullcal_headerToolbar_start,fullcal_headerToolbar_center,fullcal_headerToolbar_end;fullcal_options_additional;';

$GLOBALS['TL_DCA']['tl_module']['palettes']['fullcalendar'] = str_replace('{include_legend},form;', $fullcalPalette, $GLOBALS['TL_DCA']['tl_module']['palettes']['form']);
$GLOBALS['TL_DCA']['tl_module']['config']['onsubmit_callback'][] = ['tl_module_fullcal', 'checkCalNoSpan'];

$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_range'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['fullcal_range'],
    'exclude' => true,
    'inputType' => 'select',
    'options' => array('3_months', '6_months', '1_year', '2_years'),
    'reference' => &$GLOBALS['TL_LANG']['tl_module']['fullcal_range'],
    'eval' => ['tl_class' => 'w50'],
    'sql' => "varchar(255) NOT NULL default '1_year'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_weekNumbers'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['fullcal_weekNumbers'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50'],
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_fixedWeekCount'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['fullcal_fixedWeekCount'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50'],
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_contentHeight'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['fullcal_contentHeight'],
    'exclude' => true,
    'default' => '',
    'inputType' => 'text',
    'eval' => ['tl_class' => 'w50'],
    'sql' => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_aspectRatio'] = [ // 1.35
    'label' => &$GLOBALS['TL_LANG']['tl_module']['fullcal_aspectRatio'],
    'exclude' => true,
    'default' => '1.35',
    'inputType' => 'text',
    'eval' => ['rgxp' => 'digit', 'tl_class' => 'w50'],
    'sql' => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_isRTL'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['fullcal_isRTL'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50'],
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_wrapTitleMonth'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['fullcal_wrapTitleMonth'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50'],
    'sql' => "char(1) NOT NULL default '1'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_headerToolbar_start'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['fullcal_headerToolbar_start'],
    'exclude' => true,
    'inputType' => 'text',
    'default' => 'prev,next today',
    'sql' => "varchar(255) NOT NULL default ''",
    'eval' => ['tl_class' => 'w50'],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_headerToolbar_center'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['fullcal_headerToolbar_center'],
    'exclude' => true,
    'default' => 'title',
    'inputType' => 'text',
    'sql' => "varchar(255) NOT NULL default ''",
    'eval' => ['tl_class' => 'w50'],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_headerToolbar_end'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['fullcal_headerToolbar_end'],
    'exclude' => true,
    'default' => 'month,agendaWeek,agendaDay',
    'inputType' => 'text',
    'sql' => "varchar(255) NOT NULL default ''",
    'eval' => ['tl_class' => 'w50'],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_options_additional'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['fullcal_options_additional'],
    'exclude' => true,
    'inputType' => 'textarea',
    'sql' => "text NULL",
    'eval' => ['tl_class' => 'long', 'useRawRequestData' => true],
];

class tl_module_fullcal extends Backend
{
    public function checkCalNoSpan(DataContainer $dc)
    {
        $id = intval($dc->id);
        $moduleObj = \ModuleModel::findByPk($id);

        if ($moduleObj && $moduleObj->type === 'fullcalendar') {
            $moduleObj->cal_noSpan = '1';
            $moduleObj->save();
        }
    }
}
