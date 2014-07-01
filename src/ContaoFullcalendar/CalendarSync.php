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

class CalendarSync extends \Backend {


    public function syncCal() {


        $calObj     = \CalendarModel::findByPk(\Input::get('id'));
        $davManager = WebdavManager::getInstance();

        $davManager->init($calObj->fullcal_baseUri, $calObj->fullcal_username, $calObj->fullcal_password);

        $file = $davManager->getFile($calObj->fullcal_path);
        var_dump($file);


        // $this->redirect(\Environment::get('script').'?do=calendar');
    }
}