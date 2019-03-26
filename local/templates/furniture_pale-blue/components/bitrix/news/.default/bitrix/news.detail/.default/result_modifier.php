<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/*
В result_modifier.php расширяем $arResult добавляя в него новый ключ CANONICAL, значением которого будет являться поля привязанного элемента и с помощью функции CBitrixComponent::setResultCacheKeys передаем данный ключ в некешируемую область, файл component_epilog.php
*/

if ($arParams['CANONICAL']) {
    $arFilter = [
        'IBLOCK_ID' => $arParams['CANONICAL'],
        'PROPERTY_CANONICAL' => $arResult['ID'], // ID новости
    ];

    $arSelect = [
        'ID',
        'IBLOCK_ID',
        'NAME',
        'PROPERTY_CANONICAL',
    ];

    $r = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);

    if ($res = $r->Fetch()) {
        $arResult['CANONICAL'] = $res;
    }

    $this->__component->SetResultCacheKeys(['CANONICAL']);
}

//dump($arParams['CANONICAL']);
//dump($arResult['CANONICAL']);