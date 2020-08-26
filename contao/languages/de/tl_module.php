<?php

$fullcalendarDesc = 'Eine durch Komma (zusammenhängende Buttons) bzw. Leerzeichen (separate Buttons) getrennte Liste der folgenden Werte:
 title, today, prev, next, prevYear, nextYear,
 month, basicWeek, basicDay, agendaWeek, agendaDay, listYear, listMonth, listWeek, listDay';

$GLOBALS['TL_LANG']['tl_module']['fullcal_legend'] = 'FullCalendar';
$GLOBALS['TL_LANG']['tl_module']['fullcal_range'] = ['Zeitraum', 'Zeitraum für den im Kalender Termine angezeigt werden sollen. Abhängig vom aktuellen Datum.'];
$GLOBALS['TL_LANG']['tl_module']['fullcal_weekNumbers'] = ['Kalenderwochen anzeigen', 'Sollen Kalenderwochen anzeigt werden?'];
$GLOBALS['TL_LANG']['tl_module']['fullcal_fixedWeekCount'] = ['Feste Wochenanzahl', 'Feste Wochenanzahl (Siehe fixedWeekCount in API)'];
$GLOBALS['TL_LANG']['tl_module']['fullcal_isRTL'] = ['Schreibrichtung RTL', 'Schreibrichtung von rechts nach links?'];
$GLOBALS['TL_LANG']['tl_module']['fullcal_wrapTitleMonth'] = ['Zeilenumbruch im Titel (Monatsübersicht)', 'Soll der Titel in der Monatsübersicht umgebrochen werden? Der Titel wird dann mehrzeilig angezeigt'];
$GLOBALS['TL_LANG']['tl_module']['fullcal_contentHeight'] = ['Kalenderhöhe', 'Kalenderhöhe festlegen. Mögliche Werte: "" (Leerstring, benutze Seitenverhältnis), "auto" (Keine Scrollbars), Angabe in Pixeln'];
$GLOBALS['TL_LANG']['tl_module']['fullcal_aspectRatio'] = ['Seitenverhältnis', 'Seitenverhältnis des Kalenders Breite = [WERT] x Höhe'];

$GLOBALS['TL_LANG']['tl_module']['fullcal_headerToolbar_start'] = ['Kopfzeile (Start)', $fullcalendarDesc];
$GLOBALS['TL_LANG']['tl_module']['fullcal_headerToolbar_center'] = ['Kopfzeile (Mitte)', $fullcalendarDesc];
$GLOBALS['TL_LANG']['tl_module']['fullcal_headerToolbar_end'] = ['Kopfzeile (Ende)', $fullcalendarDesc];

$GLOBALS['TL_LANG']['tl_module']['fullcal_tooltip_options'] = ['Tooltip-Konfiguration', 'Bsp: <strong>{theme: "light"}</strong> Die Eigenschaften content und allowHTML werden automatisch gesetzt. Fügen Sie eine Tooltip-Konfiguration hinzu (Einzelheiten siehe <a target="_blank" href="https://atomiks.github.io/tippyjs/">tippy.js Dokumentation</a>)'];
$GLOBALS['TL_LANG']['tl_module']['fullcal_options_additional'] = ['Zusätzliche FullCalendar Einstellungen', 'Muss ein valides Javascript Objekt sein.'];

$GLOBALS['TL_LANG']['tl_module']['fullcal_range']['3_months'] = '-3 Monate bis +3 Monate';
$GLOBALS['TL_LANG']['tl_module']['fullcal_range']['6_months'] = '-6 Monate bis +6 Monate';
$GLOBALS['TL_LANG']['tl_module']['fullcal_range']['1_year'] = '-1 Jahr bis +1 Jahr';
$GLOBALS['TL_LANG']['tl_module']['fullcal_range']['2_years'] = '-2 Jahre bis +2 Jahre';
