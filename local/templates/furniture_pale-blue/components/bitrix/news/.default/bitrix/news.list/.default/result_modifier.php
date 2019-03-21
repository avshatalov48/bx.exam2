<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/*
В result_modifier.php из массива $arResult берется дата активности новости отображаемой первой на странице и передается в файл component_epilog.php с помощью функции CBitrixComponent::setResultCacheKeys
*/

$arResult['SPECIALDATE'] = $arResult['ITEMS'][0]['ACTIVE_FROM'];
$this->__component->SetResultCacheKeys(['SPECIALDATE']);

//dump($arResult['SPECIALDATE']);