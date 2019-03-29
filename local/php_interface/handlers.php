<?php
/**
 * Обработчики
 */
use Bitrix\Main\EventManager;

EventManager::getInstance()->addEventHandler(
    "iblock",
    "OnBeforeIBlockElementUpdate",
    [
        "Exam2\Handlers\DeactivationProductEx2Task50",
        "onBeforeIBlockElementUpdateHandler"
    ]
);