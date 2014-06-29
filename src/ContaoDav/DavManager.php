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

class DavManager extends \System {


    public function testConnection() {
        $id     = (\Input::get('id') !== null) ? intval(\Input::get('id')) : 0;
        if ($id === 0) {
            return false;
        }

        $davObj   = SabredavModel::findByPk($id);
        $settings = array(
            'baseUri'  => $davObj->baseUri,
            'userName' => $davObj->username,
            'password' => \Encryption::decrypt($davObj->password)
        );

        $client   = new \Sabre\DAV\Client($settings);
        // $response = $client->request('GET');


        $response = $client->request('HEAD');
        $features = $client->options();
        var_dump($features);
        die();

        if ($response['statusCode'] === 200) {
            \Message::add(sprintf('OK!  %s [%s]',$settings['baseUri'], $settings['userName']), 'TL_INFO');
        }
        elseif ($response['statusCode'] === 404) {
            \Message::add($settings['baseUri'].' not found. [404]', 'TL_ERROR');
        }
        elseif ($response['statusCode'] === 401) {
            $body = str_replace(array('<p>', '</p>'),array('<br>','<br>'), $response['body']);
            \Message::add(strip_tags($body,'<br>'), 'TL_ERROR');
        }
        else {
            \Message::add($response['statusCode'], 'TL_ERROR');
        }
        \Controller::redirect(\Environment::get('script').'?do=sabredav');
    }


}