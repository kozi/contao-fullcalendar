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
    private $calObj = null;

    public function syncCal() {
        header('Content-Type: text/html; charset=utf-8');

        $this->calObj    = \CalendarModel::findByPk(\Input::get('id'));

        try {
            $content = $this->getFileContent();
        }
        catch(\Exception $e) {
            \Message::add($e->getMessage(), 'TL_ERROR');
            $this->redirect(\Environment::get('script').'?do=calendar');
        }

        $vcalendar = \Sabre\VObject\Reader::read($content);
        foreach($vcalendar->VEVENT as $vevent) {
            EventMapper::saveAsCalendarEventsModel($this->calObj->id, $vevent);
        }

        $this->redirect(\Environment::get('script').'?do=calendar');
    }


    private function getFileContent() {

        $settings  = array(
            'baseUri'  => $this->calObj->fullcal_baseUri,
            'userName' => $this->calObj->fullcal_username,
            'password' => \Encryption::decrypt($this->calObj->fullcal_password)
        );

        $client    = new \Sabre\DAV\Client($settings);
        $response  = $client->request('GET', $this->calObj->fullcal_path);
        if ($response['statusCode'] === 200) {
            return $response['body'];
        }
        elseif ($response['statusCode'] === 404) {
            throw new \Exception($settings['baseUri'].' not found. [404]');
        }
        elseif ($response['statusCode'] === 401) {
            $body = str_replace(array('<p>', '</p>'),array('<br>','<br>'), $response['body']);
            throw new \Exception(strip_tags($body, '<br>'));
        }
        else {
            throw new Exception($response['statusCode']);
        }
    }

}