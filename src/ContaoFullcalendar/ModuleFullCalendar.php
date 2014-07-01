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

namespace ContaoFullcalendar;

/**
 * Class ModuleFullCalendar
 *
 * Front end module "fullcalendar".
 * @copyright  Martin Kozianka 2014 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de/>
 * @package    contao-fullcalendar
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


        $this->fullcal_viewButtons    = array('month', 'agendaWeek', 'agendaDay');

        $fullcalOptions               = new \stdClass();
        $fullcalOptions->firstDay     = $this->cal_startDay;
        $fullcalOptions->weekNumbers  = $this->fullcal_weekNumbers;
        $fullcalOptions->weekMode     = $this->fullcal_weekMode;
        $fullcalOptions->aspectRatio  = $this->fullcal_aspectRatio;
        $fullcalOptions->isRTL        = $this->fullcal_isRTL;

        $fullcalOptions->header         = new \stdClass();
        $fullcalOptions->header->left   = $this->fullcal_header_left;
        $fullcalOptions->header->center = $this->fullcal_header_center;
        $fullcalOptions->header->right  = $this->fullcal_header_right;


        $this->Template->fullcalOptions = json_encode($fullcalOptions, JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT);



        if ($objPage->hasJQuery !== '1') {
            $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/fullcalendar/assets/jquery/dist/jquery.min.js|static';
        }

        $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/fullcalendar/assets/moment/min/moment.min.js|static';

        $GLOBALS['TL_CSS'][]        = 'system/modules/fullcalendar/assets/fullcalendar/dist/fullcalendar.css||static';
        $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/fullcalendar/assets/fullcalendar/dist/fullcalendar.js|static';

        $pathLang = 'system/modules/fullcalendar/assets/fullcalendar/dist/lang/'.$objPage->language.'.js';
        if (file_exists(TL_ROOT.'/'.$pathLang)) {
            $GLOBALS['TL_JAVASCRIPT'][] = $pathLang.'|static';
        }
    }

}