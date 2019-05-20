<?php
/**
 * Обработчики событий
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

// [ex2-95] Упростить меню в адмистративном разделе для контент-менеджера
EventManager::getInstance()->addEventHandler(
    "main",
    "OnBuildGlobalMenu",
    [
        "Exam2\Handlers\ContentMenuEx2Task95",
        "onBuildGlobalMenuHandler"
    ]
);

// [ex2-93] Записывать в Журнал событий - открытие не существующих страниц сайта
// Событие вызывается в конце визуальной части эпилога сайта
// @see https://dev.1c-bitrix.ru/api_help/main/events/onepilog.php
EventManager::getInstance()->addEventHandler(
    "main",
    "OnEpilog",
    [
        "Exam2\Handlers\Check404Ex2Task93",
        "onEpilogHandler"
    ]
);