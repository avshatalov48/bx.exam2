<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/*
В component_epilog.php происходит проверка на наличие в массиве $arResult ключа CANONICAL и если условие выполняется, мы устанавливаем заданное свойство страницы
*/

// У автора в тексте - if($arParams['CANONICAL'] == 'Y'){ - так работать не будет
if ($arParams['CANONICAL']) {
    $APPLICATION->SetPageProperty("canonical", $arResult['CANONICAL']['NAME']);
}

//dump($arParams['CANONICAL']);
//dump($arResult['CANONICAL']);