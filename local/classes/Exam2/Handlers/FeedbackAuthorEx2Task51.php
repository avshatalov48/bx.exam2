<?php

namespace Exam2\Handlers;

use Bitrix\Main\Localization\Loc;

Loc::LoadMessages(__FILE__);

/**
 * [ex2-51] Изменение данных в письме
 *
 * Class FeedbackAuthorEx2Task51
 * @package Exam2\Handlers
 */
class FeedbackAuthorEx2Task51
{
    /**
     * @param $event "Идентификатор типа почтового события"
     * @param $lid "ID сайта, на котором был вызов метода CEvent::Send()"
     * @param $arFields "Массив параметров, которые передаются в обработчик события."
     */
    public static function onBeforeEventAddHandler(&$event, &$lid, &$arFields)
    {
        if ($event === "FEEDBACK_FORM") {
            global $USER;
            if ($USER->IsAuthorized()) {
                // Авторизован
                $arFields["AUTHOR"] = Loc::getMessage('USER_AUTHORIZED_1') . $USER->GetID() .
                    " (" . $USER->GetLogin() . ") " . $USER->GetFullName() .
                    Loc::getMessage('USER_AUTHORIZED_2') . $arFields["AUTHOR"];
            } else {
                // Не авторизован
                $arFields["AUTHOR"] = Loc::getMessage('USER_NOT_AUTHORIZED') . $arFields["AUTHOR"];
            }

            // Настройки > Инструменты > Журнал событий
            \CEventLog::Add(array(
                "SEVERITY" => "SECURITY",
                "AUDIT_TYPE_ID" => Loc::getMessage('USER_REPLACE'),
                "MODULE_ID" => "main",
                "DESCRIPTION" => Loc::getMessage('USER_REPLACE') . " – [" . $arFields["AUTHOR"] . "]",
            ));
        }
    }
}