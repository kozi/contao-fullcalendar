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

$this->loadLanguageFile('tl_module');

$paletteDefault = &$GLOBALS['TL_DCA']['tl_calendar']['palettes']['default'];
$GLOBALS['TL_DCA']['tl_calendar']['palettes']['default']                    = $paletteDefault.';{fullcal_legend:hide},fullcal_alias,fullcal_color,fullcal_type';
$GLOBALS['TL_DCA']['tl_calendar']['palettes']['__selector__'][]             = 'fullcal_type';

$GLOBALS['TL_DCA']['tl_calendar']['list']['label']['label_callback']        = ['tl_calendar_fullcal', 'labelWithColor'];
$GLOBALS['TL_DCA']['tl_calendar']['subpalettes']['fullcal_type_webdav']     = 'fullcal_baseUri,fullcal_path,fullcal_username,fullcal_password,fullcal_range';
$GLOBALS['TL_DCA']['tl_calendar']['subpalettes']['fullcal_type_public_ics'] = 'fullcal_ics,fullcal_range';

array_insert($GLOBALS['TL_DCA']['tl_calendar']['list']['operations'], 0, [
    'fullcal' => [
        'label' => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal'],
        'href'  => 'key=fullcal',
        'icon'  => 'reload.gif',
        'button_callback' => ['tl_calendar_fullcal', 'btnCallback']
    ]
]);

$GLOBALS['TL_DCA']['tl_calendar']['fields']['fullcal_range'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal_range'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => ['3_months','6_months','1_year','2_years'],
    'reference'               => &$GLOBALS['TL_LANG']['tl_module']['fullcal_range'],
    'eval'                    => ['tl_class' => 'w50', 'mandatory' => true],
    'sql'                     => "varchar(255) NOT NULL default 'next_365'",
];

$GLOBALS['TL_DCA']['tl_calendar']['fields']['fullcal_alias'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal_alias'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => ['rgxp'=>'alias', 'unique'=>true, 'maxlength'=>128, 'tl_class'=>'w50'],
    'save_callback'           => [['tl_calendar_fullcal', 'generateAlias']],
    'sql'                     => "varchar(128) COLLATE utf8_bin NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_calendar']['fields']['fullcal_color'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal_color'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => ['maxlength'=>6, 'multiple'=>true, 'size'=>2, 'colorpicker'=>true, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard'],
    'sql'                     => "varchar(64) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_calendar']['fields']['fullcal_type'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal_type'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => ['webdav', 'public_ics'],
    'reference'               => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal_type'],
    'sql'                     => "varchar(16) NOT NULL default ''",
    'eval'                    => [
        'submitOnChange'     => true,
        'tl_class'           => 'long',
        'includeBlankOption' => true,
        'blankOptionLabel'   => $GLOBALS['TL_LANG']['tl_calendar']['fullcal_type']['no_sync']
    ],
];

$GLOBALS['TL_DCA']['tl_calendar']['fields']['fullcal_baseUri'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal_baseUri'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'sql'                     => "varchar(255) NOT NULL default ''",
    'eval'                    => ['mandatory' => true, 'tl_class' => 'long'],
];

$GLOBALS['TL_DCA']['tl_calendar']['fields']['fullcal_ics'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal_ics'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'sql'                     => "varchar(255) NOT NULL default ''",
    'eval'                    => ['mandatory' => true, 'tl_class' => 'long'],
];

$GLOBALS['TL_DCA']['tl_calendar']['fields']['fullcal_path'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal_path'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'sql'                     => "varchar(255) NOT NULL default ''",
    'eval'                    => ['mandatory' => true, 'tl_class' => 'long'],
];

$GLOBALS['TL_DCA']['tl_calendar']['fields']['fullcal_username'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal_username'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'sql'                     => "varchar(255) NOT NULL default ''",
    'eval'                    => ['mandatory' => true, 'tl_class' => 'w50'],
];

$GLOBALS['TL_DCA']['tl_calendar']['fields']['fullcal_password'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal_password'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'sql'                     => "blob NULL",
    'eval'                    => ['mandatory' => true, 'tl_class' => 'w50', 'encrypt' => true],
];

$GLOBALS['TL_DCA']['tl_calendar']['fields']['fullcal_lastchanged'] = [
    'sql'                     => "int(10) unsigned NOT NULL default '0'"
];

class tl_calendar_fullcal extends Backend
{

    public function btnCallback($row, $href, $label, $title, $icon, $attributes)
    {
        return ($row['fullcal_type'] !== '') ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : '';
    }

    public function labelWithColor($row, $label, DataContainer $dc, $args = null)
    {
        $arrColor = deserialize($row['fullcal_color']);
        $strColor = sprintf('<span class="fullcal_color" style="background-color:#%s;">&nbsp;&nbsp;&nbsp;</span> ', $arrColor[0]);
        return $strColor.$label;
    }


    public function generateAlias($varValue, DataContainer $dc)
    {
        $autoAlias = false;

        // Generate alias if there is none
        if ($varValue == '')
        {
            $autoAlias = true;
            $varValue = standardize(StringUtil::restoreBasicEntities($dc->activeRecord->title));
        }

        $objAlias = $this->Database->prepare("SELECT id FROM tl_calendar WHERE fullcal_alias=?")
            ->execute($varValue);

        // Check whether the news alias exists
        if ($objAlias->numRows > 1 && !$autoAlias)
        {
            throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
        }

        // Add ID to alias
        if ($objAlias->numRows && $autoAlias)
        {
            $varValue .= '-' . $dc->id;
        }

        return $varValue;
    }
}
