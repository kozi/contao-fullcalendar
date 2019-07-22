<?php

namespace ContaoFullcalendar;

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2019 Leo Feyer
 *
 * PHP version 5
 * @copyright Martin Kozianka 2014-2019 <http://kozianka.de/>
 * @author    Martin Kozianka <http://kozianka.de/>
 * @package   contao-fullcalendar
 * @license   LGPL
 * @filesource
 */

class InfoObject
{
    /**
     * Exception
     * @var \Exception
     */
    private $objException = null;
    private $strType = 'TL_INFO';
    private $strTitle;
    private $intNew;
    private $intUpdated;
    private $intDeleted;

    public function __construct($objCal)
    {
        $this->strTitle = $objCal->title;
        $this->intNew = 0;
        $this->intUpdated = 0;
        $this->intDeleted = 0;
    }

    public function add($objEvent)
    {
        if ($objEvent->fullcal_flagNew) {
            $this->intNew++;
        } else {
            $this->intUpdated++;
        }
    }

    public function setDeleted($intDel)
    {
        $this->intDeleted = $intDel;
    }

    public function getMessage()
    {
        if ($this->objException !== null) {
            return $this->objException->getMessage();
        }

        return sprintf(
            'Kalender <strong>%s</strong>: %s Events eingefügt, %s Events aktualisiert, %s Events gelöscht',
            $this->strTitle, $this->intNew, $this->intUpdated, $this->intDeleted
        );
    }

    public function setException(\Exception $e)
    {
        $this->strType = 'TL_ERROR';
        $this->objException = $e;
    }

    public function getType()
    {
        return $this->strType;
    }

}
