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

namespace ContaoSabredav;

class SabredavManager extends \System {


    public function testConnection() {
        $id     = (\Input::get('id') !== null) ? intval(\Input::get('id')) : 0;
        if ($id === 0) {
            return false;
        }

        $davObj = SabredavModel::findByPk($id);
        var_dump($davObj);
    }


}