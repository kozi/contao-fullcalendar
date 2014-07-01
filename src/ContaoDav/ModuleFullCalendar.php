<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2014 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2014 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de/>
 * @package    contao-dav
 * @license    LGPL
 * @filesource
 */

namespace ContaoDav;

/**
 * Class ModuleFullCalendar
 *
 * Front end module "fullcalendar".
 * @copyright  Martin Kozianka 2014 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de/>
 * @package    contao-dav
 */
class ModuleFullCalendar extends \Events {

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_fullcalendar';

    public function generate() {
        if (TL_MODE == 'BE') {
            $objTemplate           = new \BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['fullcalendar'][0]) . ' ###';
            $objTemplate->title    = $this->headline;
            $objTemplate->id       = $this->id;
            $objTemplate->link     = $this->name;
            $objTemplate->href     = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
            return $objTemplate->parse();
        }
        return parent::generate();
    }

    protected function compile() {
        global $objPage;

        $GLOBALS['TL_CSS'][] = 'system/modules/dav/assets/fullcalendar/fullcalendar.css||static';

        //$objLayout = $this->getPageLayout($objPage);
        if ($objPage->hasJQuery !== '1') {
            $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/dav/assets/fullcalendar/lib/jquery.min.js|static';
        }
        $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/dav/assets/fullcalendar/lib/moment.min.js|static';
        $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/dav/assets/fullcalendar/fullcalendar.js|static';

    }

}