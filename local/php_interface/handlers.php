<?php
/**
 * Обработчики
 */
use Bitrix\Main\EventManager;

// [ex2-50] Проверка при деактивации товара
EventManager::getInstance()->addEventHandler(
    "iblock",
    "OnBeforeIBlockElementUpdate",
    [
        "Exam2\Handlers\DeactivationProductEx2Task50",
        "onBeforeIBlockElementUpdateHandler"
    ]
);

// [ex2-51] Изменение данных в письме
EventManager::getInstance()->addEventHandler(
    "main",
    "OnBeforeEventAdd",
    [
        "Exam2\Handlers\FeedbackAuthorEx2Task51",
        "onBeforeEventAddHandler"
    ]
);