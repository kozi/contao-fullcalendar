<?php

$this->loadLanguageFile('tl_module');

$paletteDefault = &$GLOBALS['TL_DCA']['tl_calendar']['palettes']['default'];
$GLOBALS['TL_DCA']['tl_calendar']['palettes']['default']        = $paletteDefault.';{fullcal_legend:hide},fullcal_color,fullcal_type';
$GLOBALS['TL_DCA']['tl_calendar']['palettes']['__selector__'][] = 'fullcal_type';


$GLOBALS['TL_DCA']['tl_calendar']['list']['label']['label_callback'] = array('tl_calendar_fullcal', 'labelWithColor');

$GLOBALS['TL_DCA']['tl_calendar']['subpalettes']['fullcal_type_webdav']         = 'fullcal_baseUri,fullcal_path,fullcal_username,fullcal_password,fullcal_range';
$GLOBALS['TL_DCA']['tl_calendar']['subpalettes']['fullcal_type_public_ics']     = 'fullcal_ics,fullcal_range';


array_insert($GLOBALS['TL_DCA']['tl_calendar']['list']['operations'], 0, array
(
    'fullcal' => array
    (
        'label' => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal'],
        'href'  => 'key=fullcal',
        'icon'  => 'reload.gif',
        'button_callback' => array('tl_calendar_fullcal', 'btnCallback')
    )
));

$GLOBALS['TL_DCA']['tl_calendar']['fields']['fullcal_range'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal_range'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('3 months','6 months','1 year','2 years'),
    'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
    'eval'                    => array('tl_class' => 'w50', 'mandatory' => true),
    'sql'                     => "varchar(255) NOT NULL default 'next_365'",
);

$GLOBALS['TL_DCA']['tl_calendar']['fields']['fullcal_color'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal_color'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('maxlength'=>6, 'multiple'=>true, 'size'=>2, 'colorpicker'=>true, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard'),
    'sql'                     => "varchar(64) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_calendar']['fields']['fullcal_type'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal_type'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('' => 'No Sync', 'webdav' => 'webdav', 'public_ics' => 'public_ics'),
    'sql'                     => "varchar(16) NOT NULL default ''",
    'eval'                    => array('submitOnChange'=>true, 'tl_class' => 'w50'),
);

$GLOBALS['TL_DCA']['tl_calendar']['fields']['fullcal_baseUri'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal_baseUri'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'sql'                     => "varchar(255) NOT NULL default ''",
    'eval'                    => array('mandatory' => true, 'tl_class' => 'long'),
);

$GLOBALS['TL_DCA']['tl_calendar']['fields']['fullcal_ics'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal_ics'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'sql'                     => "varchar(255) NOT NULL default ''",
    'eval'                    => array('mandatory' => true, 'tl_class' => 'long'),
);

$GLOBALS['TL_DCA']['tl_calendar']['fields']['fullcal_path'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal_path'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'sql'                     => "varchar(255) NOT NULL default ''",
    'eval'                    => array('mandatory' => true, 'tl_class' => 'long'),
);

$GLOBALS['TL_DCA']['tl_calendar']['fields']['fullcal_username'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal_username'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'sql'                     => "varchar(255) NOT NULL default ''",
    'eval'                    => array('mandatory' => true, 'tl_class' => 'w50'),

);

$GLOBALS['TL_DCA']['tl_calendar']['fields']['fullcal_password'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal_password'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'sql'                     => "blob NULL",
    'eval'                    => array('mandatory' => true, 'tl_class' => 'w50', 'encrypt' => true),
);

$GLOBALS['TL_DCA']['tl_calendar']['fields']['fullcal_lastchanged'] = array(
    'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

class tl_calendar_fullcal extends Backend {

    public function btnCallback($row, $href, $label, $title, $icon, $attributes) {
        return ($row['fullcal_type'] !== '') ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : '';
    }

    public function labelWithColor($row, $label, DataContainer $dc, $args = null) {
        $arrColor = deserialize($row['fullcal_color']);
        $strColor = sprintf('<span class="fullcal_color" style="background-color:#%s;">&nbsp;&nbsp;&nbsp;</span> ', $arrColor[0]);
        return $strColor.$label;
    }

}