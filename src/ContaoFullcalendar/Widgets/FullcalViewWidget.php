<?php

namespace ContaoFullcalendar\Widgets;

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2017 Leo Feyer
 *
 * PHP version 5
 * @copyright Martin Kozianka 2014-2017 <http://kozianka.de/>
 * @author    Martin Kozianka <http://kozianka.de/>
 * @package    contao-fullcalendar
 * @license    LGPL
 * @filesource
 */

class FullcalViewWidget extends \Widget
{
    protected $strTemplate = 'be_widget';
    private $strReturn = '';
    private $tmplRow   = '<tr class="%s"><td class="key">%s</td><td class="value">%s</td></tr>';
    private $lang      = null;
    private $event     = null;

    public function generate()
    {
        $this->lang  = &$GLOBALS['TL_LANG']['tl_calendar_events'];
        $this->event = &$this->activeRecord;

        $this->strReturn  = '<table class="fullcalView"><tbody>';

        $this->getTblRow('title', null, 'h');

        $this->getTblRow('fullcal_time', $this->fullcal_time());
        $this->getTblRow('tstamp', \Date::parse('d.m.Y H:i:s', $this->event->tstamp));

        $this->getTblRow('location');
        $this->getTblRow('alias');
        $this->getTblRow('fullcal_uid');
        $this->getTblRow('fullcal_desc');

        $this->strReturn .= '</tbody></table>';

        return $this->strReturn;
    }

    private function fullcal_time()
    {
        $tle       = new \tl_calendar_events();
        $strReturn = $tle->listEvents($this->event->row());
        $strReturn = preg_replace('/.*?\[(.*?)\].*/i', "$1", $strReturn);
        return $strReturn;
    }

    private function getTblRow($key, $value = null)
    {
        $value = ($value === null) ? $this->event->$key : $value;
        $this->strReturn .= sprintf($this->tmplRow, $key, $this->lang[$key][0], $value);
        return ;
    }
}
