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

class FullcalendarRunonceJob extends \Controller
{
	public function run()
    {
        $distPath = "system/modules/fullcalendar/assets/dist";
        if (is_dir(TL_ROOT."/".$distPath))
        {
            $objFolder = new \Folder($distPath);
            $objFolder->delete();
        }		
    }
}

$objFullcalendarRunonceJob = new FullcalendarRunonceJob();
$objFullcalendarRunonceJob->run();
