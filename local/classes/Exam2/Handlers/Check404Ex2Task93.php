<?php

namespace Exam2\Handlers;

use Bitrix\Main\Localization\Loc;

Loc::LoadMessages(__FILE__);

/**
 * [ex2-93] Записывать в Журнал событий - открытие не существующих страниц сайта
 *
 * Class Check404Ex2Task93
 *
 * @package Exam2\Handlers
 */
class Check404Ex2Task93
{
    public static function onEpilogHandler()
    {
        if (defined("ERROR_404") && ERROR_404 === "Y") {
            global $APPLICATION;

            \CEventLog::Add(
                [
                    "SEVERITY"      => "INFO",
                    "AUDIT_TYPE_ID" => "ERROR_404",
                    "MODULE_ID"     => "main",
                    // Возвращает путь к текущей странице относительно корня вместе с параметрами
                    "DESCRIPTION"   => $APPLICATION->GetCurUri(),
                ]
            );
        }
    }
}