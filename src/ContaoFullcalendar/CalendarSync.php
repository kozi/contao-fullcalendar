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
            if (!is_string($content) || strlen($content) === 0) {
                \Message::add("Sync error (".$this->calObj->fullcal_type.').', 'TL_ERROR');
                $this->redirect(\Environment::get('script').'?do=calendar');
            }
        }
        catch(\Exception $e) {
            \Message::add($e->getMessage(), 'TL_ERROR');
            $this->redirect(\Environment::get('script').'?do=calendar');
        }

        $arrEventIds = array();
        $vcalendar   = \Sabre\VObject\Reader::read($content);
        $infoObj     = (object) array('new' => 0, 'updated' => 0, 'deleted' => 0);
        foreach($vcalendar->VEVENT as $vevent) {
            $evObj = EventMapper::saveAsCalendarEventsModel($this->calObj->id, $vevent);
            $arrEventIds[] = $evObj->id;
            ($evObj->fullcal_flagNew) ? $infoObj->new++: $infoObj->updated++;
        }

        // Events löschen die nicht gefunden wurden
        if (count($arrEventIds) > 0) {
            $stmt = \Database::getInstance()->prepare(
                "DELETE FROM tl_calendar_events WHERE pid=?
                AND fullcal_uid != ''
                AND id not in(".
                implode(',', $arrEventIds).")");
            $stmt->execute($this->calObj->id);
            $infoObj->deleted = $stmt->affectedRows;
        }

        \Message::add(sprintf(
            '%s Events eingefügt, %s Events aktualisiert, %s Events gelöscht',
            $infoObj->new, $infoObj->updated, $infoObj->deleted
        ),'TL_INFO');

        // TODO Termine vom Sync ausnehmen über uid

        $this->redirect(\Environment::get('script').'?do=calendar');
    }


    private function getFileContent() {
        if ('public_ics' === $this->calObj->fullcal_type) {
            $content = file_get_contents($this->calObj->fullcal_ics);
            if ($content !== false) {
                return $content;
            }
            else {
                throw new \Exception('Error getting content from '.$this->calObj->fullcal_ics);
            }
        }
        elseif ('webdav' === $this->calObj->fullcal_type) {

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

        throw new Exception('Unknown sync type '.$this->calObj->fullcal_type);
    }



}