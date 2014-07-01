<?php

$GLOBALS['TL_DCA']['tl_calendar']['palettes']['__selector__'][]  = 'fullcal_enableSync';
$GLOBALS['TL_DCA']['tl_calendar']['palettes']['default']        .= ';{fullcal_legend:hide},fullcal_enableSync';
$GLOBALS['TL_DCA']['tl_calendar']['subpalettes']['fullcal_enableSync'] =
    'fullcal_baseUri,fullcal_path,fullcal_username,fullcal_password';



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


$GLOBALS['TL_DCA']['tl_calendar']['fields']['fullcal_enableSync'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal_enableSync'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'sql'                     => "char(1) NOT NULL default ''",
    'eval'                    => array('submitOnChange'=>true),
);

$GLOBALS['TL_DCA']['tl_calendar']['fields']['fullcal_baseUri'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar']['fullcal_baseUri'],
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

class tl_calendar_fullcal extends Backend {

    public function btnCallback($row, $href, $label, $title, $icon, $attributes) {
        return ($row['fullcal_enableSync'] === '1') ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : '';
    }
}