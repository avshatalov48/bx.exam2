<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/*
В component_epilog.php происходит проверка на активность чекбокса и в положительном случае устанавливается новое свойство страницы следующим образом
*/

//dump($arResult['SPECIALDATE']);
//dump($arParams['SPECIALDATE']);

if ($arParams['SPECIALDATE'] == 'Y') {
    $APPLICATION->SetPageProperty("specialdate", $arResult['SPECIALDATE']);
}