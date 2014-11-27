<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2014 Leo Feyer
 *
 * PHP version 5
 * @copyright Martin Kozianka 2014 <http://kozianka.de/>
 * @author    Martin Kozianka <http://kozianka.de/>
 * @package   contao-fullcalendar
 * @license   LGPL
 * @filesource
 */

namespace ContaoFullcalendar;

use \Sabre\VObject\Reader;
use \Sabre\DAV\Client;

use \Contao\Folder;
use \Contao\File;

class CalendarSync extends \Backend {
    public static $icsFolder = 'share/ics-events/';
    public static $calFolder = 'share/ics-cal/';

    public function syncOneCal() {
        $calObj = \CalendarModel::findByPk(\Input::get('id'));
        if ($calObj) {
            $infoObj = $this->updateCalendar($calObj);
            \Message::add($infoObj->getMessage(), $infoObj->getType());
        }

        if (\Input::get('id') && \Input::get('table') === 'tl_calendar_events') {
            $this->redirect(
                \Environment::get('script')
                .'?do=calendar&table=tl_calendar_events&id='.\Input::get('id')
            );
        }
        $this->redirect(\Environment::get('script').'?do=calendar');
    }

    public function syncCal() {
        $objCalendarCollection = \CalendarModel::findAll(array(
            'column' => array("fullcal_type != ''"),
        ));

        foreach($objCalendarCollection as $objCalendar) {
            $infoObj = self::updateCalendar($objCalendar);
            $this->log(strip_tags($infoObj->getMessage()), __METHOD__, TL_CRON);
        }
    }

    public function clearIcsFolder() {
        $folder = new Folder(static::$calFolder);
        $folder->purge();
        $this->log('Purge folder '.static::$calFolder, __METHOD__, TL_CRON);

        $folder = new Folder(static::$icsFolder);
        $folder->purge();
        $this->log('Purge folder '.static::$icsFolder, __METHOD__, TL_CRON);

        $this->syncCal();
    }

    private function updateCalendar(\Model $objCalendar) {
        $vcalContent = null;
        $infoObj     = new InfoObject($objCalendar);

        try {
            $vcalContent = self::getVCalendarContent($objCalendar);
        }
        catch(\Exception $e) {
            $infoObj->setException($e);
            return $infoObj;
        }


        // Time range
        $arrEventIds   = array();
        $dateTimeStart = new \DateTime('-'.$objCalendar->fullcal_range);
        $dateTimeEnd   = new \DateTime('+'.$objCalendar->fullcal_range);

        $vcalendar     = Reader::read($vcalContent);

        // Lokale Version des Kalenders speichern
        $strFilename = static::$calFolder.$objCalendar->fullcal_alias.'.ics';
        $file = new File($strFilename);
        $file->write($vcalContent);
        $file->close();


        $vcalendar->expand($dateTimeStart, $dateTimeEnd);

        if($vcalendar->VEVENT) {
            foreach($vcalendar->VEVENT as $vevent) {
                $evObj         = EventMapper::getCalendarEventsModel($vevent, $objCalendar);
                $arrEventIds[] = intval($evObj->id);
                $infoObj->add($evObj);
            }
        }

        // Events löschen die nicht gefunden wurden
        if (count($arrEventIds) > 0) {
            $stmt = \Database::getInstance()->prepare(
                "DELETE FROM tl_calendar_events WHERE pid = ? AND fullcal_id != ''"
                .(empty($arrEventIds) ? "" : " AND id not in(".implode(',', $arrEventIds).")")
            );
            $stmt->execute($objCalendar->id);
            $infoObj->setDeleted($stmt->affectedRows);

        }

        // Add errors in infoObj;
        return $infoObj;
    }

    /**
     * Get vcalendar string
     * @param $calObj
     * @throws \Exception
     * @internal param $ \CalendarModel
     *
     * @return string
     */
    private static function getVCalendarContent($calObj) {
        $content = self::getFileContent($calObj);

        if (!is_string($content) || strlen($content) === 0) {
            throw new \Exception('Could not get content.');
        }

        return $content;
    }


    /**
     * Get remote file content
     * @param $calObj
     * @throws \Exception
     * @internal param $ \CalendarModel
     * @return string
     */
    private static function getFileContent($calObj) {
        if ('public_ics' === $calObj->fullcal_type) {

            // fullcal_lastchanged
            $content = file_get_contents($calObj->fullcal_ics);
            if ($content !== false) {
                return $content;
            }
            else {
                throw new \Exception('Error getting content from '.$calObj->fullcal_ics);
            }
        }
        elseif ('webdav' === $calObj->fullcal_type) {

            $settings  = array(
                'baseUri'  => $calObj->fullcal_baseUri,
                'userName' => $calObj->fullcal_username,
                'password' => \Encryption::decrypt($calObj->fullcal_password)
            );

            $client    = new Client($settings);

            // Hack!
            $client->addCurlSetting(CURLOPT_SSL_VERIFYPEER, 0);
            $client->addCurlSetting(CURLOPT_SSL_VERIFYHOST, 0);

            $response  = $client->request('GET', $calObj->fullcal_path);
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
                throw new \Exception($response['statusCode']);
            }
        }

        throw new \Exception('Unknown sync type '.$calObj->fullcal_type);
    }
}

