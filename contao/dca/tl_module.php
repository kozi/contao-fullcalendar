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
{fullcalendar_legend},cal_startDay,fullcal_isRTL;
{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';


$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_weekNumbers'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['fullcal_weekNumbers'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'sql'                     => "char(1) NOT NULL default ''",
    'eval'                    => array(),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_weekMode'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['fullcal_weekNumbers'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'sql'                     => "char(1) NOT NULL default ''",
    'eval'                    => array(),
    // 'fixed' options = array('fixed', 'liquid', 'variable');
);

$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_aspectRatio'] = array( // 1.35
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['fullcal_weekNumbers'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'sql'                     => "char(1) NOT NULL default ''",
    'eval'                    => array(),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['fullcal_isRTL'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['fullcal_isRTL'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'sql'                     => "char(1) NOT NULL default ''",
    'eval'                    => array(),
);
