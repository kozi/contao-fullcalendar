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

class WebdavManager {

    /**
     * @var WebdavManager
     */
    protected static $instance;

    /**
     * Client
     * @var \Sabre\DAV\Client
     */
    private $client = null;


    /**
     * Instantiate the WebdavManager object (Factory)
     *
     * @return WebdavManager
     */
    static public function getInstance() {

        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function getFile($url) {
        $this->client->request($url);
    }

    public function init($baseUri, $username, $password) {
        $settings  = array(
            'baseUri'  => $baseUri,
            'userName' => $username,
            'password' => $password
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

}

