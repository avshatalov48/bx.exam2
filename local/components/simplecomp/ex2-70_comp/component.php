<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;

// Приводим значения к числу
$arParams["IBLOCK_CATALOG_ID"] = (int)$arParams["IBLOCK_CATALOG_ID"];
$arParams["IBLOCK_NEWS_ID"] = (int)$arParams["IBLOCK_NEWS_ID"];

// Значение по умолчанию, не обязательно, но желательно
if (!$arParams["CACHE_TIME"]) {
    $arParams["CACHE_TIME"] = 3600;
}