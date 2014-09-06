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

use Contao\CalendarModel;

class FullCalTags extends \Frontend {

    public function replaceTags($strTag) {
        $strReturn     = '';
        $tagValues     = trimsplit('::', $strTag);
        $this->tagname = array_shift($tagValues);

        // Get ics calendar url
        if ($this->tagname === 'fullcal_url' || $this->tagname === 'fullcal_link') {
            if (count($tagValues) === 0) {
                return sprintf('{{%s}} Error: No params given', $strTag);
            }
            if(is_numeric($tagValues[0])) {
                $calObj = CalendarModel::findByPk($tagValues[0]);
            } else {
                $calObj = CalendarModel::findOneBy('fullcal_alias', $tagValues[0]);
            }

            if ($calObj === null) {
                return sprintf('{{%s}} Error: No calendar found', $strTag);
            }

            $calUrl = \Environment::get('url').'/'.CalendarSync::$calFolder.$calObj->fullcal_alias.'.ics';

            if ($this->tagname === 'fullcal_url') {
                return $calUrl;
            }

            return sprintf('<a href="%s" title="%s [%s]">%s</a>',
                    $calUrl,
                    $calObj->title,
                    $calUrl,
                    (($tagValues[1]) ? $tagValues[1] : $calObj->title)
            );

        }

        // Nicht unser tag
        return false;
    }



}

