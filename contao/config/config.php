<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2014 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2014 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de/>
 * @package    contao-sabredav
 * @license    LGPL
 * @filesource
 */

$GLOBALS['BE_MOD']['system']['sabredav'] = array(
    'tables'     => array('tl_sabredav'),
    'icon'       => 'system/modules/sabredav/assets/drive-network.png',
    'test'       => array('SabredavManager', 'testConnection')
);
