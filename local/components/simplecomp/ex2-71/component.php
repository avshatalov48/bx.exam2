<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;

global $USER;

// [ex2-107]
global $CACHE_MANAGER;

if (!isset($arParams["CACHE_TIME"])) {
    $arParams["CACHE_TIME"] = 36000000;
}

// ex2-60
// Получаем параметры постраничной навигации
$arNavigation = CDBResult::GetNavParams($arNavParams);

// Условия кеширования результата работы компонента - зависит от группы текущего пользователя
// Код кэша из news.list
if ($this->startResultCache(false, [
    ($arParams["CACHE_GROUPS"] === "N" ? false : $USER->GetGroups()),
    $arNavigation,
])) {
    if (!Loader::includeModule("iblock")) {
        $this->abortResultCache();
        ShowError(GetMessage("EX2_71_IB_CHECK"));
        return;
    }

    // [ex2-107]
    // Помечаем кэш тегом
    $CACHE_MANAGER->RegisterTag("iblock_id_" . SERVICES_IBLOCK_ID);

    // Получаем классификатор
    $arResult["CLASS"] = [];

    $arSelectClass = [
        "ID",
        "IBLOCK_ID",
        "NAME",
    ];

    $arFilterClass = [
        "IBLOCK_ID"         => $arParams["ID_IB_CLASS"],
        "CHECK_PERMISSIONS" => $arParams["CACHE_GROUPS"],
        "ACTIVE"            => "Y",
    ];

    $resElements = \CIBlockElement::GetList(
        false,
        $arFilterClass,
        false,
        // ex2-60
        // "nPageSize" - количество элементов на странице при постраничной навигации
        // "bShowAll" - разрешить вывести все элементы при постраничной навигации
        ["nPageSize" => $arParams['ELEMENTS_PER_PAGE'], "bShowAll" => false],
        $arSelectClass
    );

    // ex2-60
    // Задаём "NAV_TITLE"
    $arResult["NAV_STRING"] = $resElements->GetPageNavString(GetMessage("EX2_60_PAGES"));

    while ($arElement = $resElements->GetNext()) {
        $arResult["CLASS"][$arElement["ID"]] = $arElement;
    }

    $arClassIDs = array_column($arResult["CLASS"], "ID");
    $arResult["CLASS_COUNT"] = count($arResult["CLASS"]);

    // Получаем элементы из каталога
    $arSelectElems = [
        "ID",
        "IBLOCK_ID",
        "NAME",
        // URL ссылки на детальный просмотр
        "DETAIL_PAGE_URL",
    ];

    $arFilterElems = [
        "IBLOCK_ID"                              => $arParams["ID_IB_CATALOG"],
        "CHECK_PERMISSIONS"                      => $arParams["CACHE_GROUPS"],
        "PROPERTY_" . $arParams["CODE_PROPERTY"] => $arClassIDs,
        "ACTIVE"                                 => "Y",
    ];

    $arSortElems = [
        "SORT" => "ASC",
    ];

    $arResult["ELEMENTS"] = [];

    $resElements = \CIBlockElement::GetList(
        $arSortElems,
        $arFilterElems,
        false,
        false,
        $arSelectElems
    );

    while ($ob = $resElements->GetNextElement()) {
        $arEl = $ob->GetFields();
        // Т.к. св-во "FIRMS" множественное, а в ИБ хранятся св-ва в одной таблице (не в отдельной),
        // чтобы не было дублей - получаем св-ва через отдельный метод
        $arEl["PROPS"] = $ob->GetProperties();

        $arResult["ELEMENTS"][$arEl["ID"]] = $arEl;
    }

    // Группируем элементы по классификаторам
    foreach ($arResult["CLASS"] as $iClass => $arClass) {
        foreach ($arResult["ELEMENTS"] as $iEl => $arEl) {
            foreach ($arEl["PROPS"]["FIRMS"]["VALUE"] as $iVal) {
                if ($iVal == $iClass) {
                    $arResult["CLASS"][$iVal]["ELEMENTS_ID"][] = $arEl["ID"];
                    break;
                }
            }
        }
    }

    $this->SetResultCacheKeys(["CLASS_COUNT"]);

    $this->includeComponentTemplate();
} else {
    $this->abortResultCache();
}

// В компоненте устанавливать заголовок страницы: «Разделов: [Количество]».
// Где Количество – количество элементов классификатора.
//
// Заголовок должен устанавливаться в файле component.php.
// Этот функционал является логикой компонента и не должен «теряться» при смене шаблона.
$APPLICATION->SetTitle(GetMessage("EX2_71_SECTIONS") . $arResult["CLASS_COUNT"]);