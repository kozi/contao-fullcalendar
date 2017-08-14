<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2017 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2014-2017 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de/>
 * @package    contao-fullcalendar
 * @license    LGPL
 * @filesource
 */

use ContaoFullcalendar\Modules\ModuleFullCalendar;

class FullcalendarRunonceJob extends \Controller
{
	public function run()
    {
        if (is_dir(TL_ROOT."/".ModuleFullCalendar::$distPath))
        {
            $objFolder = new \Folder(ModuleFullCalendar::$distPath);
            $objFolder->delete();
        }		
    }
}

$objFullcalendarRunonceJob = new FullcalendarRunonceJob();
$objFullcalendarRunonceJob->run();
