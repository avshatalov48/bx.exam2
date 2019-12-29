<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;

if (!isset($arParams["CACHE_TIME"])) {
    $arParams["CACHE_TIME"] = 36000000;
}

if (!Loader::includeModule("iblock")) {
    ShowError(GetMessage("EX2_71_IB_CHECK"));
    return;
}

// Условия кеширования результата работы компонента - зависит от группы текущего пользователя
if ($this->startResultCache(false, [$USER->GetGroups()])) {
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
        false,
        $arSelectClass
    );

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

        foreach ($arEl["PROPS"]["FIRMS"]["VALUE"] as $iVal) {
            $arResult["CLASS"][$iVal]["ELEMENTS_ID"][] = $arEl["ID"];
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