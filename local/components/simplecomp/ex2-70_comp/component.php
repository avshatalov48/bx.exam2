<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;

// Подготавливаем пришедшие параметры
$arParams["IBLOCK_CATALOG_ID"] = (int)$arParams["IBLOCK_CATALOG_ID"];
$arParams["IBLOCK_NEWS_ID"] = (int)$arParams["IBLOCK_NEWS_ID"];

if (empty($arParams["CACHE_TIME"])) {
    $arParams["CACHE_TIME"] = 3600;
}

if ($this->startResultCache(false, ($arParams["CACHE_GROUPS"] === "N" ? false : $USER->GetGroups()))) {
    if (!Loader::includeModule("iblock")) {
        $this->abortResultCache();
        ShowError(GetMessage("EX2_70_IB_CHECK"));
        return;
    }

    // <Выбор новостей>
    $arNewsIDs = [];
    $arNewsList = [];

    $resNews = \CIBlockElement::GetList(
        [],
        [
            'IBLOCK_ID' => $arParams['IBLOCK_NEWS_ID'],
            'ACTIVE'    => 'Y',
        ],
        false,
        false,
        [
            'NAME',
            'ACTIVE_FROM',
            'ID',
        ]
    );

    while ($arNewsElement = $resNews->Fetch()) {
        $iElID = $arNewsElement['ID'];
        $arNewsIDs[] = $iElID;
        $arNewsList[$iElID] = $arNewsElement;
    }
    // </Выбор новостей>


    // <Выбор разделов с привязкой к новостям>
    $arSectionIDs = [];
    $arSectionList = [];
    $resSections = \CIBlockSection::GetList(
        [],
        [
            'IBLOCK_ID'                => $arParams['IBLOCK_CATALOG_ID'],
            $arParams['USER_PROPERTY'] => $arNewsIDs,
            'ACTIVE'                   => 'Y',
        ],
        true,
        [
            'NAME',
            'IBLOCK_ID',
            'ID',
            $arParams['USER_PROPERTY'],
        ],
        false
    );

    while ($arSection = $resSections->Fetch()) {
        $iElID = $arSection['ID'];
        $arSectionIDs[] = $iElID;
        $arSectionList[$iElID] = $arSection;
    }
    // </Выбор разделов с привязкой к новостям>


    // <Выбор товаров с привязкой разделов, привязанных к новостям>
    $resProducts = \CIBlockElement::GetList(
        [],
        [
            'IBLOCK_ID'  => $arParams['IBLOCK_CATALOG_ID'],
            'ACTIVE'     => 'Y',
            'SECTION_ID' => $arSectionIDs,
        ],
        false,
        false,
        [
            'NAME',
            'IBLOCK_SECTION_ID',
            'ID',
            'IBLOCK_ID',
            'PROPERTY_ARTNUMBER',
            'PROPERTY_MATERIAL',
            'PROPERTY_PRICE',
        ]
    );
    // </Выбор товаров с привязкой разделов, привязанных к новостям>

    $arResult['PRODUCT_COUNT'] = 0;

    // Распределение продукции по новостям
    while ($arProduct = $resProducts->Fetch()) {
        $arResult['PRODUCT_COUNT']++;
        foreach ($arSectionList[$arProduct['IBLOCK_SECTION_ID']]['UF_NEWS_LINK'] as $iNewsID) {
            $arNewsList[$iNewsID]['PRODUCTS'][] = $arProduct;
        }
    }

    // Распределение разделов по новостям
    foreach ($arSectionList as $arSection) {
        foreach ($arSection['UF_NEWS_LINK'] as $iNewsID) {
            $arNewsList[$iNewsID]['SECTIONS'][] = $arSection['NAME'];
        }
    }

    $arResult['NEWS'] = $arNewsList;

    $this->SetResultCacheKeys(['PRODUCT_COUNT']);

    $this->IncludeComponentTemplate();
} else {
    $this->abortResultCache();
}

$APPLICATION->SetTitle(GetMessage('EX2_70_ELEMENTS_COUNT') . $arResult['PRODUCT_COUNT']);