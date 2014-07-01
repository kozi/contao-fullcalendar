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

$GLOBALS['TL_DCA']['tl_module']['palettes']['fullcalendar'] = '{title_legend},name,headline,type;
{fullcal_legend},cal_startDay,fullcal_weekMode,fullcal_aspectRatio,fullcal_weekNumbers,fullcal_isRTL;
fullcal_header_left,fullcal_header_center,fullcal_header_right;
{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';


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
    'eval'                    => array('rgxp' => 'digit'),
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
