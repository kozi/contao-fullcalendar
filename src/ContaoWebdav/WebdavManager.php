<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2014 Leo Feyer
 *
 *
 * PHP version 5
 * @copyright  Martin Kozianka 2014 <http://kozianka.de/>
 * @author     Martin Kozianka <http://kozianka.de/>
 * @package    contao-webdav
 * @license    LGPL
 * @filesource
 */

namespace ContaoWebdav;

class WebdavManager extends \System {

    /**
     * Client
     * @var \Sabre\DAV\Client
     */
    private $client = null;

    private function initWebdavClient($id) {
        $webdavObj = WebdavModel::findByPk($id);
        $settings  = array(
            'baseUri'  => $webdavObj->baseUri,
            'userName' => $webdavObj->username,
            'password' => \Encryption::decrypt($webdavObj->password)
        );

        $this->client = new \Sabre\DAV\Client($settings);
        $response     = $this->client->request('HEAD');

        if ($response['statusCode'] === 200) {
            return true;
        }
        elseif ($response['statusCode'] === 404) {
            throw new Exception($settings['baseUri'].' not found. [404]', 'TL_ERROR');
        }
        elseif ($response['statusCode'] === 401) {
            $body = str_replace(array('<p>', '</p>'),array('<br>','<br>'), $response['body']);
            throw new Exception(strip_tags($body,'<br>'));
        }
        else {
            throw new Exception($response['statusCode']);
        }
    }

    public function testConnection() {
        $id     = (\Input::get('id') !== null) ? intval(\Input::get('id')) : 0;
        if ($id === 0) {
            return false;
        }

        try {
            if ($this->initWebdavClient($id) === true) {
                \Message::add('Connection successful!', 'TL_INFO');
            }
        }
        catch (Exception $e) {
            \Message::add($e->getMessage(), 'TL_ERROR');
        }
        \Controller::redirect(\Environment::get('script').'?do=webdav');
    }


    public function updateFiles() {
        $id     = (\Input::get('id') !== null) ? intval(\Input::get('id')) : 0;
        if ($id === 0) {
            return false;
        }

        try {
            $this->initWebdavClient($id);
        }
        catch (Exception $e) {
            \Message::add($e->getMessage(), 'TL_ERROR');
            \Controller::redirect(\Environment::get('script').'?do=webdav');
        }

        $this->updateMappings($id);
        die();
        \Controller::redirect(\Environment::get('script').'?do=webdav');
    }


    private function updateMappings($pid) {
        $mappingObj = WebdavMappingModel::findBy('pid', $pid);
        foreach ($mappingObj as $mapObj){
            var_dump($mapObj);
        }
    }
}

